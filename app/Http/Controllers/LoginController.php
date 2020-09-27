<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class LoginController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'unauthorized']
        ]);
    }
    
    function unauthorized()
    {
        return $this->response(401, __('auth.unauthorized'));
    }
    
    function login(Request $request)
    {
        $token = $this->attempt($request);

        if (! $token) {
            return $this->response(400, __('auth.failed'));
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

}
