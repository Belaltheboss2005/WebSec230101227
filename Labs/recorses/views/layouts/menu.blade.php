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
                <a class="nav-link" href="{{ url('/test') }}">Test</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/minitest') }}">Mini Test</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/transcript') }}">Student Transcript</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/products') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/calculator') }}">Calculator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/products2') }}">Products 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/users2') }}">Users 2</a>
            </li>
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
