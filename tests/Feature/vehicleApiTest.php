<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class vehicleApiTest extends TestCase
{
    protected $user;
    protected $vehicle;

    public function test_api_take_and_leave_vehicle()
    {
        $this->apiRequest([
            'method' => 'GET',
            'uri' => route('api.vehicle.take', [], false),
            'data' => [
                'vehicle_id' => $this->vehicle->id,
                'user_id' => $this->user->id
            ]
        ])->assertOk();

        $this->apiRequest([
            'method' => 'GET',
            'uri' => route('api.vehicle.leave', [], false),
            'data' => [
                'vehicle_id' => $this->vehicle->id
            ],
        ])->assertOk();
    }

    public function test_take_non_exiting_vehicle()
    {
        $this->apiRequest([
            'method' => 'GET',
            'uri' => route('api.vehicle.take', [], false),
            'data' => [
                'user_id' => $this->user->id,
                'vehicle_id' => 0
            ],
        ])->assertStatus(422);
    }

    public function test_leave_non_exiting_vehicle()
    {
        $this->apiRequest([
            'method' => 'GET',
            'uri' => route('api.vehicle.leave', [], false),
            'data' => [
                'vehicle_id' => 0
            ],
        ])->assertStatus(422);
    }

    protected function apiRequest($request)
    {
        return $this->json($request['method'], $request['uri'], $request['data']);
    }

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('config:clear');
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');

        $this->user = User::inRandomOrder()->first();
        $this->vehicle = Vehicle::inRandomOrder()->first();
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:rollback');

        parent::tearDown();
    }
}
