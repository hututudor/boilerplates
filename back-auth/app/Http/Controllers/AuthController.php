<?php

namespace App\Http\Controllers;

use App\Preference;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;

class AuthController extends Controller
{
    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch(JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json(compact('user', 'token'));
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:30']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function getAuthenticatedUser() {
        $user = self::getUser();

        if(!$user) {
            return response()->json('', 403);
        }

        return response()->json(compact('user'));
    }

    // this method returns the authenticated user if there is one
    public static function getUser() {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return null;
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return $user;
    }

    public function changePass(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'max:255'],
            'new' => ['required', 'string', 'max:255']
        ]);
        if($validator->fails() ) {
            return response()->json($validator->errors(), 400);
        }
        $user = AuthController::getUser();
        if(!$user) {
            return response()->json('', 404);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json('', 403);
        }
        $user->password = Hash::make($request->new);
        $user->save();
        return response()->json('', 200);
    }

    public function changeName(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]);
        if($validator->fails() ) {
            return response()->json($validator->errors(), 400);
        }
        $user = AuthController::getUser();
        if(!$user) {
            return response()->json('', 404);
        }
        $user->name = $request->name;
        $user->save();
        return response()->json(array(['name' => $user->name]), 200);
    }
}
