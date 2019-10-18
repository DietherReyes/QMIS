@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">

        <h1>Welcom to Quality Management Information System</h1>

        @guest
            <p> <a class="btn btn-primary btn-lg" href="{{route('login')}}">LOGIN</a> </p>
        @else
            <ul>
                <li> <a class="btn btn-primary btn-lg" style="width:300px" href="/csm">CSM</a> </li>
                @if(Auth::user()->role === 'admin')
                <li>  <a class="btn btn-primary btn-lg" style="width:300px" href="/sysmg/accounts">SYSTEM MANAGEMENT</a> </li>
                @endif
            </ul>    
        <p>     </p>
        @endguest
    </div>
@endsection
