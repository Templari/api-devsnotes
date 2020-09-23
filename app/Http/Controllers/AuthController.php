<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{

    private $response = ['error' => false];
    private $returnCode = 200;

    function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'create', 'unauthorized']
        ]);
    }
    
    function unauthorized()
    {
        $this->response['error'] = __('auth.unauthorized');
        $this->returnCode = 401;
        return $this->response();
    }

    function create(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validation = $this->validation($data);

        if ($validation->fails()) {
            $this->response['error'] = $validation->errors();
            $this->returnCode = 422;
            return $this->response();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $this->attempt($request);

        if (! $token) {
            $this->response['error'] = true;
            $this->returnCode = 500;
            return $this->response();
        }

        $this->response['token'] = $token;
        return $this->response();
    }
    
    function login(Request $request)
    {
        $token = $this->attempt($request);

        if (! $token) {
            $this->response['error'] = __('auth.failed');
            $this->returnCode = 400;
            return $this->response();
        }
        
        $this->response['token'] = $token;
        return $this->response();
    }
    
    function logout()
    {
        auth()->logout();
        return $this->response();
    }

    function refresh()
    {
        $this->response['token'] = auth()->refresh();
        return $this->response();
    }

    private function validation($data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'min:4', 'confirmed'],
        ]);
    }

    private function attempt(Request $request)
    {
        return auth()->attempt([
            'email' => $request->input('email', ''),
            'password' => $request->input('password', ''),
        ]);
    }

    private function response()
    {
        return response()->json($this->response, $this->returnCode);
    }

}
