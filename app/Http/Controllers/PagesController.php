<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class PagesController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function csm(){
        return view('pages.csm');
    }

    public function sysmg_accounts(){
        
        $users = User::all();
        return view('pages.sysmg_accounts')->with('users',$users);
    }

    public function sysmg_units(){
        return view('pages.sysmg_units');
    }

    public function sysmg_signatories(){
        return view('pages.sysmg_signatories');
    }
}
