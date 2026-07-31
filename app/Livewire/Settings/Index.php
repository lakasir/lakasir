<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Services\Tenants\AboutService;
use Laravel\Pennant\Feature;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

#[Layout('layouts.app', ['title' => 'Pengaturan Sistem - Lakasir POS', 'header' => 'Pengaturan Sistem'])]
class Index extends Component
{
    use WithFileUploads;

    public $activeTab = 'about';

    // About (Toko)
    public $about = [
        'shop_name' => '',
        'shop_location' => '',
        'currency' => 'IDR',
        'photo' => null,
    ];
    public $newPhoto;

    // App & Features
    public $setting = [
        'minimum_stock_nofication' => 5,
        'default_tax' => 0,
    ];
    
    public $feature = [
        'supplier' => false,
        'purchasing' => false,
        'receivable' => false,
        'stock-opname' => false,
        'voucher' => false,
        'pos-v2' => false,
        'product-import' => false,
    ];

    // Profile
    public $profile = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public function mount()
    {
        $this->loadAbout();
        $this->loadSettings();
        $this->loadProfile();
    }

    public function loadAbout()
    {
        $aboutData = About::first();
        if ($aboutData) {
            $this->about['shop_name'] = $aboutData->shop_name ?? '';
            $this->about['shop_location'] = $aboutData->shop_location ?? '';
            $this->about['currency'] = $aboutData->currency ?? 'IDR';
            $this->about['photo'] = $aboutData->photo ?? null;
        }
    }

    public function loadSettings()
    {
        foreach (config('setting.key', ['minimum_stock_nofication', 'default_tax']) as $key) {
            $this->setting[$key] = Setting::get($key);
        }

        foreach (array_keys($this->feature) as $feat) {
            $this->feature[$feat] = Feature::active($feat);
        }
    }

    public function loadProfile()
    {
        /** @var User $user */
        $user = auth()->user();
        $profile = $user->profile;

        $this->profile['name'] = $user->name;
        $this->profile['email'] = $user->email;
        if ($profile) {
            $this->profile['phone'] = $profile->phone;
            $this->profile['address'] = $profile->address;
        }
    }

    public function saveAbout(AboutService $aboutService)
    {
        $this->validate([
            'about.shop_name' => 'required',
            'about.shop_location' => 'required',
            'about.currency' => 'required',
        ]);

        if ($this->newPhoto) {
            $filename = $this->newPhoto->storePublicly('public');
            $this->about['photo_url'] = \Illuminate\Support\Facades\Storage::url($filename);
        }

        $aboutService->createOrUpdate($this->about);
        session()->flash('success', 'Profil toko berhasil diperbarui.');
        $this->loadAbout();
    }

    public function saveSettings()
    {
        foreach ($this->setting as $key => $value) {
            Setting::set($key, $value);
        }

        if (can('access feature flag')) {
            foreach ($this->feature as $name => $value) {
                if ($value) {
                    Feature::activate($name);
                } else {
                    Feature::deactivate($name);
                }
            }
        }

        session()->flash('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }

    public function saveProfile()
    {
        $this->validate([
            'profile.name' => 'required',
            'profile.email' => 'required|email',
            'profile.password' => 'nullable|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $userProfile = $user->profile;

        $userUpdate = [
            'name' => $this->profile['name'],
            'email' => $this->profile['email'],
        ];

        if (!empty($this->profile['password'])) {
            $userUpdate['password'] = Hash::make($this->profile['password']);
        }

        $user->update($userUpdate);
        
        if ($userProfile) {
            $userProfile->update([
                'phone' => $this->profile['phone'],
                'address' => $this->profile['address'],
            ]);
        }

        session()->flash('success', 'Profil akun berhasil diperbarui.');
        $this->profile['password'] = '';
        $this->profile['password_confirmation'] = '';
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
