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

    public function insufficient_records(){
        return view('pages.insufficient_records');
    }

}
