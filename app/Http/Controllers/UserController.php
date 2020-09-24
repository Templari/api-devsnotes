<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;

class UserController extends Controller
{
    
    function view($id = 0)
    {
        $user = User::find($id);

        if (! $user) {
            $this->response['error'] = __('auth.failed');
            $this->returnCode = 400;
            return $this->response();
        }

        $this->response['user'] = $this->userData($user);
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

        $this->response['user'] = $this->userData($user, true);
        return $this->response();
    }

    function list()
    {
        $users = User::all();

        if (! count($users)) {
            $this->response['error'] = __('auth.500');
            $this->returnCode = 500;
            return $this->response();
        }

        foreach ($users as $user) {
            $this->response['users'][] = $this->userData($user);
        }

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

    private function userData($user)
    {
        $id = $this->loggedUser ? $this->loggedUser->id : null;
        $current = $user->id == $id;

        return [
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'current_user' => $current,
        ];
    }

}
