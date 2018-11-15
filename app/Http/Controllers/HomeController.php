<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;

use App\Response\Response;

class HomeController extends Controller
{
    public function home() {
        return response()->json(Response::toString(true, "Api Connect"));
    }

    public function toApi() {
        return Redirect::to("api");
    }
}
