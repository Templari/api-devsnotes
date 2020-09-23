<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'create', 'unauthorized']
        ]);
    }

    function create(Request $request)
    {
        $response = ['errors' => false];
        $returnCode = 200;

        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validation = $this->validation($data);

        if ($validation->fails()) {
            $response['errors'] = $validation->errors();
            $returnCode = 422;
            return response()->json($response, $returnCode);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = auth()->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (! $token) {
            $response['errors'] = true;
            $returnCode = 500;
            return response()->json($response, $returnCode);
        }

        $response['token'] = $token;
        
        return response()->json($response, $returnCode);
    }
    
    function login()
    {}
    
    function logout()
    {}
    
    function unauthorized()
    {}

    private function validation($data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'min:4', 'confirmed'],
        ]);
    }

}
