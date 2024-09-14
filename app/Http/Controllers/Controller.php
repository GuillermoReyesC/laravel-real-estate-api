<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Real state",
 *         version="1.0.0",
 *         description="API para gestionar propiedades inmobiliarias"
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="apiKeyAuth",
 *             type="apiKey",
 *             in="header",
 *             name="X-RapidAPI-Key"
 *         )
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
