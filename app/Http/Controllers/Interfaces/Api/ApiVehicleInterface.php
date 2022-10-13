<?php

namespace App\Http\Controllers\Interfaces\Api;

use App\Http\Requests\Api\ApiVehicleTakeRequest;
use App\Http\Requests\Api\ApiVehicleLeaveRequest;
use App\Http\Resources\ApiVehicleResource;

interface ApiVehicleInterface
{
    /**
     * @param ApiVehicleTakeRequest $request
     * @return ApiVehicleResource
     *
     * @OA\Get(
     *     path="/api/vehicle_take",
     *     tags={"vehicle_take"},
     *     summary="Vehicle Take",
     *     @OA\Parameter(
     *         name="vehicle_id",
     *         in="query",
     *         description="Vehicle ID",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="data",
     *                      @OA\Property(
     *                          property="message",
     *                          type="string",
     *                      )
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *     )
     * )
     */
    public function take(ApiVehicleTakeRequest $request): ApiVehicleResource;


    /**
     * @param ApiVehicleLeaveRequest $request
     * @return ApiVehicleResource
     *
     * @OA\Get(
     *     path="/api/vehicle_leave",
     *     tags={"vehicle_leave"},
     *     summary="Vehicle Leave",
     *     @OA\Parameter(
     *         name="vehicle_id",
     *         in="query",
     *         description="Vehicle ID",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="data",
     *                      @OA\Property(
     *                          property="message",
     *                          type="string",
     *                      )
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *     )
     * )
     */
    public function leave(ApiVehicleLeaveRequest $request): ApiVehicleResource;
}
