<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class LoginController extends Controller
{

    function __construct()
    {
        parent::__construct(['login', 'unauthorized']);
    }
    
    function unauthorized()
    {
        return $this->response(401, __('statuses.401'));
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
