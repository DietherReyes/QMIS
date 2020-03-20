<nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <font color="white" size="1">
                            <img style="max-width:36px; margin-top: -5px; margin-left: -20px"
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/DOST_seal.svg/1010px-DOST_seal.svg.png">
                        </font>
                        <strong>  {{ config('app.name', 'Laravel') }} </strong>
                    </a>
                </div>

                

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    
                    
                    @guest
                    @else

                    <!-- Left Side Of Navbar -->


                    <ul class="nav navbar-nav">
                        <li><a href="/csm">CSM</a></li>
                        <li><a href="/manrev">MR</a></li>
                        <li><a href="/qmsd">QMSD</a></li>  
                    </ul>

                    @endguest


                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            {{-- no button if guest --}}
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">

                                    <li> <a href="/profiles/{{Auth::user()->id}}">Profile</a> </li>

                                    @if( Auth::user()->role  === 'admin' )
                                    <li><a href="/sysmg/accounts">System Management</a></li>
                                    @endif

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>