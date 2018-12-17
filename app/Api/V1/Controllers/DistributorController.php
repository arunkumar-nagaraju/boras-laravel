<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;

class DistributorController extends Controller
{
    /**
     * Log the user in
     *
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkup( Request $request )
    {
        $credentials = $request->only(['email', 'password']);

        try {
            
        return response()
            ->json([
                'status' => 'ok',
                'credentials' => $credentials
            ]);

        } catch (JWTException $e) {
            throw new HttpException(500);
        }

    }
}
