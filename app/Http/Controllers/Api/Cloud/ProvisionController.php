<?php

namespace App\Http\Controllers\Api\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Tenants\User;
use App\Services\ProvisionCompany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Lets lakasir-cloud create a shop on this server, for the hosted model where
 * the customer runs on our machine. Guarded by a shared secret: an open
 * endpoint here would let anyone create companies on a customer's install.
 */
class ProvisionController extends Controller
{
    public function __invoke(Request $request, ProvisionCompany $provision): JsonResponse
    {
        $secret = (string) config('license.provision_secret');

        if ($secret === '') {
            return response()->json(['success' => false, 'message' => 'Provisioning is not enabled'], 404);
        }

        $given = (string) $request->header('X-Provision-Secret', '');

        if (! hash_equals($secret, $given)) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_password' => ['required', 'string', 'min:8'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
        ]);

        // Idempotent by name: a retried call must not make a second shop.
        $company = Company::where('name', $data['name'])->first();
        $created = false;

        if (! $company) {
            $owner = User::firstOrCreate(
                ['email' => $data['owner_email']],
                [
                    'name' => $data['owner_name'],
                    'password' => bcrypt($data['owner_password']),
                    'is_owner' => true,
                ],
            );

            $company = $provision->create([
                'name' => $data['name'],
                'domain' => $data['domain'] ?? null,
                'owner' => $owner,
                'trial_days' => $data['trial_days'] ?? null,
                'provisioned_by' => 'cloud',
            ]);

            $created = true;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'company_id' => $company->id,
                'name' => $company->name,
                'domain' => $company->domain,
            ],
        ], $created ? 201 : 200);
    }
}
