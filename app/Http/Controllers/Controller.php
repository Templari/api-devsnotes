<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response = ['error' => false];
    protected $returnCode = 200;
    protected $loggedUser;

    function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    protected function response()
    {
        return response()->json($this->response, $this->returnCode);
    }
}
