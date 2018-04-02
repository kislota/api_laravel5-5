<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class AuthJwtController extends Controller {


	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */

	public function __construct() {

		$this->middleware( 'auth:api', [ 'except' => [ 'login', 'register' ] ] );
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login( Request $request ) {

		$credentials = $request->only( 'email', 'password' );

		if ( $token = JWTAuth::attempt( $credentials ) ) {
			return $this->respondWithToken( $token );
		}

		return response()->json( [ 'error' => 'Unauthorized' ], 401 );
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me() {
		return response()->json( auth()->user() );
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout() {
		auth()->logout();

		return response()->json( [ 'logout' ] );
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh() {
		return $this->respondWithToken( auth()->refresh() );
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken( $token ) {
		return response()->json( [
			'access_token' => $token,
			'token_type'   => 'bearer'
		] );
	}


	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register( Request $request ) {

		$validator = Validator::make( $request->all(), [
			'email'    => 'required|string|email|max:255|unique:users',
			'name'     => 'required',
			'password' => 'required'
		] );

		if ( $validator->fails() ) {
			return response()->json( $validator->errors() );
		}
		User::create( [
			'name'     => $request->get( 'name' ),
			'email'    => $request->get( 'email' ),
			'password' => bcrypt( $request->get( 'password' ) ),
		] );
		$user  = User::first();
		$token = JWTAuth::fromUser( $user );

		return $this->respondWithToken( $token );
	}


}
