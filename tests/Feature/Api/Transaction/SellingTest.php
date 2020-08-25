<?php

namespace Tests\Feature\Api\Transaction;

use App\Traits\SetClietnCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellingTest extends TestCase
{
    use SetClietnCredentials;

    public function test_selling_list_item_success($search = null)
    {
        $this->setClientCredentialsToken();

        $response = $this->get(route('api.selling.index', [
            'search' => $search
        ]), $this->oauth_headers);

        $response->dump()
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'payload' => [
                    ['id']
                ]
            ]);
    }
}
