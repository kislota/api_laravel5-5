<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    return response()->json( User::all() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
	    $user = Auth::user();
	    $user->password = bcrypt($request->getPassword());
	    $user->save();
	    return response()->json('ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	public function forgotMail( Request $request ) {
		$user = User::whereEmail( $request->email )->first();
		if (!$user) {
			return response()->json( [ 'message' => 'bad email' ] );
		}

		$token = JWTAuth::fromUser( $user );
		$email   = $request->email;
		$name    = $user->name;
		$subject = "Please reset password.";

		\Mail::send( 'email.verify', [ 'name' => $name, 'verification_code' => $token ],
			function ( $mail ) use ( $email, $name, $subject ) {
				$mail->from( 'admin@exaple.com', "From User" );
				$mail->to( $email, $name )->subject( $subject );
			} );

		return response()->json( compact( 'token' ) );
	}

}
