<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class PagesController extends Controller
{
    
    public function __construct(){
        
        $this->middleware('auth');
        
        
    }

    public function home()
    {
        return view('pages.home');
    }

    public function unauthorized(){
        return view('pages.unauthorized');
    }

    public function deactivated(){
        return view('pages.deactivated');
    }

    public function insufficient_records(){
        return view('pages.insufficient_records');
    }

}
