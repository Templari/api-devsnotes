<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response = [];
    protected $loggedUser;

    function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    protected function response(int $returnCode = 200, $error = null)
    {
        if ($error) {
            $this->response['error'] = $error;
        }

        return response()->json($this->response, $returnCode);
    }

    protected function attempt(Request $request)
    {
        return auth()->attempt([
            'email' => $request->input('email', ''),
            'password' => $request->input('password', ''),
        ]);
    }
}
