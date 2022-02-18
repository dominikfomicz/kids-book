<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	/**
	 * @param RegisterRequest $request
	 * @return JsonResponse
	 */
	public function register(RegisterRequest $request)
	{
		$user = User::create([
			'name' => data_get($request, 'name'),
			'email' => data_get($request, 'email'),
			'password' => Hash::make(data_get($request, 'password')),
		]);

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'token' => $token,
			'type' => 'Bearer',
		]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function login(Request $request)
	{
		if (!Auth::attempt($request->only('email', 'password'))) {
			return response()->json([
				'message' => 'Invalid login details'
			], 401);
		}

		$user = User::where('email', $request['email'])->firstOrFail();

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $token,
			'token_type' => 'Bearer',
		]);
	}
}
