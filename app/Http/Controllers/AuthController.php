<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register()
    {
    	$validation = Validator::make(request()->all(),[
    		'name' => 'required|min:3',
    		'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
    		'password_confirmation'
    		]);
    	if ($validation->fails()) {
    		return response()->json([
    			'message' => 'Validation Failed. Check \'data\' for which field failed.',
    			'code' => '001',
    			'data' => $validation->errors()
    			],200);
    	}
    	try {
    		$user = User::create([
			'name' => request()->name,
			'password' => bcrypt(request()->password),
			'email' => request()->email,
			'api_token' => str_random(60)
			]);
    	} catch (\Exception $e) {
    		return response()->json([
    			'message'=> 'Something went wrong with creating the user.',
    			'code' => '111',
    			'data' => []
    			]);
    	}
    	return response()->json([
    			'message'=> 'User registration Successful.',
    			'code' => '000',
    			'data' => $user
    			]);   	
    	//Dont return the user object, just the api token 
    }

// log a user in
// accepts email and password as parameters
    public function login()
    {
    	try {
    		$user = User::where('email', request()->email)->firstOrFail();
    	} catch (ModelNotFoundException $e) {
    		return response()->json([
    			'message' => 'User not found.',
    			'code' => '003',
    			'data'=> []
    			],200);
    	}
    	
    	if(Hash::check(request()->password, $user->password)){
    		$user->update(['api_token'=>str_random(60)]);
    		return response()->json([
    			'message'=> 'User Login successful',
    			'code' => '000',
    			'data' => $user
    			//what parameters of the user are needed by the front end?
    			//Currently returning the entire user object.
    			]);
    	}
    }

//log a user out
//accepts userid
    public function logout($id)
    {
    	try {
    		$user = User::findOrFail($id);
    		if ($user->api_token == null) {
    			return response()->json([
    			'message' => 'User not logged in.',
    			'code' => '004',
    			'data'=> []
    			],200);
    		}
    		$user->update(['api_token' => null]);

    	} catch (ModelNotFoundException $e) {
    		return response()->json([
    			'message' => 'User not found. Logout operation unsuccessful.',
    			'code' => '003',
    			'data'=> []
    			],200);
    	}catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'code' => '111',
                'data'=> []
                ],200);
    	return response()->json([
    			'message' => 'User logout successful.',
    			'code' => '000',
    			'data'=> []
    			],200);
    }
}
