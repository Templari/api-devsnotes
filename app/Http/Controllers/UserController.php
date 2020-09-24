<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['create']
        ]);
    }
    
    function view($id = 0)
    {
        $user = User::find($id);

        if (! $user) {
            return $this->response(400, __('auth.failed'));
        }

        $this->response['user'] = $this->userData($user);
        return $this->response();
    }

    function viewCurrent()
    {
        $user = $this->loggedUser;

        if (! $user) {
            return $this->response(500, __('auth.500'));
        }

        $this->response['user'] = $this->userData($user, true);
        return $this->response();
    }

    function list()
    {
        $users = User::all();

        if (! count($users)) {
            return $this->response(500, __('auth.500'));
        }

        foreach ($users as $user) {
            $this->response['users'][] = $this->userData($user);
        }

        return $this->response();
    }

    function create(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validation = $this->validation($data, [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($validation->fails()) {
            return $this->response(422, $validation->errors());
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $this->attempt($request);

        if (! $token) {
            return $this->response(500, __('auth.500'));
        }

        $this->response['token'] = $token;
        return $this->response();
    }

    function update(Request $request)
    {
        $user = $this->loggedUser;

        if (! $user) {
            return $this->response(500, __('auth.500'));
        }

        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = $this->validation($data, [
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
        // TODO: apenas usuários com privilégios podem remover outros

        /* $user = User::find($id);

        if (! $user) {
            $this->response['error'] = __('auth.failed');
            return $this->response(400);
        }

        $user->delete(); */
        return $this->response(401, __('auth.unauthorized'));
    }

    private function validation($data, $additional = [])
    {
        $nameRegex = "/^[A-ZÀ-Ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-ZÀ-Ÿ][A-zÀ-ÿ']+/";
        $rules = [
            'name' => ['string', 'min:2', 'max:100', "regex: $nameRegex"],
            'email' => ['email', 'max:100'],
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
