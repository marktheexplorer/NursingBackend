<header class="header">
    <div class="page-brand">
        <a class="link" href="{{ route('dashboard') }}">
            <span class="brand">
                <span class="brand-tip">{{$_ENV['APP_NAME']}}</span>
            </span>
            <span class="brand-mini">CS</span>
        </a>
    </div>
    <div class="flexbox flex-1">
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="nav navbar-toolbar">
            <li class="dropdown dropdown-user">
                <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                    <img src="{{ asset('admin/assets/img/admin-avatar.png') }}">
                    <span></span>{{ ucfirst(Auth::user()->name) }}<i class="fa fa-angle-down m-l-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user"></i>Profile</a>
                    <a class="dropdown-item" href="{{ route('change.password') }}"><i class="fa fa-cog"></i>Change<br> Password</a>
                    <li class="dropdown-divider"></li>
                    <a  class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </li>
        </ul>
    </div>
</header>

