<?php

namespace App\Http\Requests\Auth;

use App\Tenant;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
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
        $this->merge([
            'domain' => strtolower($this->domain) . '.' . config('tenancy.central_domains')[0],
        ]);

        return [
            'name' => ['required', 'string', 'max:255', 'unique:domains,id', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'domain' => ['required', 'string', 'max:255', 'unique:domains', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'full_name' => ['required', 'string', 'max:255'],
        ];
    }


    public function register(): Tenant
    {
        try {
            $tenant = Tenant::create([
                'id' => $this->name,
                'tenancy_db_name' => 'lakasir_' . $this->name,
                'tenancy_db_profile_full_name' => $this->full_name,
                'tenancy_db_profile_email' => $this->email,
                'tenancy_db_profile_password' => bcrypt($this->password),
            ]);
            $tenant->domains()->create([
                'domain' => $this->domain,
            ]);
            $tenant->user()->create([
                'full_name' => $this->full_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            return $tenant;
        } catch (Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }
}
