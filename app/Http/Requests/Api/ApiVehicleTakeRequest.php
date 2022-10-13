<?php

namespace App\Http\Requests\Api;

final class ApiVehicleTakeRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:App\Models\User,id',
            'vehicle_id' => 'required|exists:App\Models\Vehicle,id',
        ];
    }
}
