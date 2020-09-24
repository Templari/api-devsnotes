<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    
    function view($id)
    {
        return $this->response();
    }

    function viewCurrent()
    {
        $user = $this->loggedUser;

        if (! $user) {
            $this->response['error'] = __('auth.500');
            $this->returnCode = 500;
            return $this->response();
        }

        $this->response['user'] = [
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return $this->response();
    }

    function list()
    {
        return $this->response();
    }

    function update()
    {
        return $this->response();
    }

    function delete()
    {
        return $this->response();
    }

}
