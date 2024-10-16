<?php

namespace App\Http\Requests\Auth;

use App\Enums\ShopType;
use App\Rules\Domain;
use App\Services\RegisterTenant;
use App\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    public function __construct(public RegisterTenant $registerTenant)
    {

    }

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
                'domain' => strtolower($this->domain).'.'.config('tenancy.central_domains')[0],
            ]);
        }

        return [
            'domain' => ['required', 'string', 'max:255', 'unique:domains', new Domain],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'business_type' => ['required', Rule::in(ShopType::cases())],
            'other_business_type' => ['required_if:business_type,other'],
        ];
    }

    public function register(): Tenant
    {
        try {
            $tenant = $this->registerTenant->create($this->all());

            return $tenant;
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
