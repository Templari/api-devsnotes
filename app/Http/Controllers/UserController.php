<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{

    function __construct()
    {
        parent::__construct(['create']);
    }
    
    function view($id)
    {
        $user = User::find($id);

        if (! $user) {
            return $this->response(400, __('validation.failed'));
        }

        $this->response['user'] = $this->userData($user);
        return $this->response();
    }

    function viewCurrent()
    {
        $user = $this->loggedUser;

        if (! $user) {
            return $this->response(500, __('statuses.500'));
        }

        $this->response['user'] = $this->userData($user, true);
        return $this->response();
    }

    function list()
    {
        $users = User::all();
        $this->response['users'] = [];

        foreach ($users as $user) {
            $this->response['users'][] = $this->userData($user);
        }

        return $this->response();
    }

    function create(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = $this->validator($data, [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->response(422, $validator->errors());
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $this->attempt($request);

        if (! $token) {
            return $this->response(500, __('statuses.500'));
        }

        $this->response['token'] = $token;
        return $this->response();
    }

    function update(Request $request)
    {
        $user = $this->loggedUser;

        if (! $user) {
            return $this->response(500, __('statuses.500'));
        }

        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = $this->validator($data, [
            'name' => ['nullable', 'sometimes'],
            'email' => ['nullable', 'sometimes'],
            'password' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return $this->response(422, $validator->errors());
        }

        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return $this->response();
    }

    function delete($id)
    {
        return $this->response(401, __('statuses.401'));
    }

    private function validator($data, $additional = [])
    {
        $nameRegex = "/^[A-ZÀ-Ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-ZÀ-Ÿ][A-zÀ-ÿ']+/";
        $rules = [
            'name' => ['string', 'min:2', 'max:100', "regex: $nameRegex"],
            'email' => ['email', 'max:100', 'unique:users'],
            'password' => ['min:4', 'confirmed'],
        ];

        foreach ($additional as $key => $values) {
            if (! isset($rules[$key])) continue;

            foreach ($values as $value) {
                $rules[$key][] = $value;
            }
        }

        return Validator::make($data, $rules);
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
