<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
class LogsController extends Controller
{

    public function __construct(){
        //Check if user is authenticated and administrator
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('account_status');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = Log::orderBy('created_at', 'desc')->paginate(10);
        return view('logs.index')->with('logs', $logs);
    }

    //search user
    public function search(Request $request){
        $logs = Log::where('name', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('logs.index')->with('logs', $logs);
    }

    
}
