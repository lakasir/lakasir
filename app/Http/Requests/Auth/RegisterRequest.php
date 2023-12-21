<?php

namespace App\Http\Requests\Auth;

use App\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $domain = explode('.', $this->domain);
        if (count($domain) > 2) {
            $this->merge([
                'name' => strtolower($domain[0]),
                'domain' => strtolower($this->domain),
            ]);
        } else {
            $this->merge([
                'name' => strtolower($this->domain),
                'domain' => strtolower($this->domain) . '.' . config('tenancy.central_domains')[0],
            ]);
        }

        return [
            'domain' => ['required', 'string', 'max:255', 'unique:domains', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*\.' . config('tenancy.central_domains')[0] . '$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }


    public function register(): Tenant
    {
        try {
            /** @var Tenant */
            $tenant = Tenant::create([
                'id' => $this->name,
                'tenancy_db_name' => 'lakasir_' . $this->name,
                'tenancy_db_profile_full_name' => $this->full_name ?? $this->name,
                'tenancy_db_profile_email' => $this->email,
                'tenancy_db_profile_password' => bcrypt($this->password),
            ]);
            $tenant->domains()->create([
                'domain' => $this->domain,
            ]);
            $tenant->user()->create([
                'full_name' => $this->full_name ?? $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            $tenant->user->notify(new \App\Notifications\DomainCreated());

            Artisan::call('tenants:seed', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);

            return $tenant;
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
