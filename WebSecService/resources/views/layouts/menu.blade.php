<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/even') }}">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/prime') }}">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/multable') }}">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/products') }}">Products</a>
            </li>

            @auth
                @if(auth()->user()->privilege != 0)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users') }}">Users View</a>
                    </li>
                @endif
            @endauth

            @auth
                @if(auth()->user()->privilege != 0)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/student') }}">Student View</a>
                    </li>
                @endif
            @endauth

        </ul>

        <ul class="navbar-nav">
            @auth
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('do_logout') }}">Logout</a></li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
