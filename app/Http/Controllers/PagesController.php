<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class PagesController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function unauthorized(){
        return view('pages.unauthorized');
    }

}
