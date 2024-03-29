<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class authController extends Controller
{
        public $successStatus = 200;
    //
    public function login(Request $request) {
        $request_data = $request->all();
        
        
        if ( Auth::attempt( ['email' => $request_data['email'], 'password' => $request_data['password']] )) {
            $user = Auth::user();
            $success['token']=$user->createToken('MyApp')->accessToken;

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
    }

    public function checkout() {
        
        if ( Auth::check() == true ) {
            return response()->json("login");
        }
        else if( Auth::check() == false ) {
            $user = Auth::user();
            print_r($user);exit;
            return response()->json("logout");
        }
    }
    
    public function register(Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->error()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        
        // if (User::where('email',$input['email'])) {
        //     $user = User::where('email','=', $input['email'])->get();
        //     var_dump("Exist:". $user);exit;
        //     return response()->json(['duplicate' => 'This Email address is already exist']);
        // } 

        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }
}
