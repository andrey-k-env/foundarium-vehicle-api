<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\Api\ApiVehicleInterface;
use App\Http\Requests\Api\ApiVehicleTakeRequest;
use App\Http\Requests\Api\ApiVehicleLeaveRequest;
use App\Http\Resources\ApiVehicleResource;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Client\HttpClientException;


final class ApiVehicleController extends Controller implements ApiVehicleInterface
{
    public function take(ApiVehicleTakeRequest $request): ApiVehicleResource
    {
        $validated = $request->validated();
        $vehicle = Vehicle::find($validated['vehicle_id']);
        $user = User::find($validated['user_id']);

        if ($vehicle->driver()->exists() || $user->vehicle()->exists()) {
            throw new HttpClientException('Cant take vehicle.');
        }

        $vehicle->driver()->attach($user);

        return new ApiVehicleResource($vehicle);
    }

    public function leave(ApiVehicleLeaveRequest $request): ApiVehicleResource
    {
        $validated = $request->validated();
        $vehicle = Vehicle::find($validated['vehicle_id']);

        if (! $vehicle->driver()->exists()) {
            throw new HttpClientException('Vehicle is busy.');
        }

        $user = $vehicle->driver()->first();
        $vehicle->driver()->detach($user);

        return new ApiVehicleResource($vehicle);
    }
}
