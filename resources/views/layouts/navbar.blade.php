<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            @role('admin')
            <li class="ml-3">
                <a href="{{ route('ticket.create') }}" class="btn btn-primary text-dark">
                    <i class="fas fa-ticket-alt"></i><span> Ticket ochish</span>
                </a>
            </li>
            @endrole
            @role('contract')
            <li class="ml-3">
                <a href="{{ route('ticket.create') }}" class="btn btn-primary text-dark">
                    <i class="fas fa-ticket-alt"></i><span> Ticket ochish</span>
                </a>
            </li>
            @endrole
            @role('admin')
            <li class="ml-3">
                <a href="{{ route('ticket.activeList') }}" class="btn btn-primary text-dark">
                    <i class="fas fa-clipboard-list"></i><span> Aktiv buyurtmalar</span>
                </a>
            </li>
            @endrole
            @role('contract')
            <li class="ml-3">
                <a href="{{ route('ticket.activeList') }}" class="btn btn-primary text-dark">
                    <i class="fas fa-clipboard-list"></i><span> Aktiv buyurtmalar</span>
                </a>
            </li>
            @endrole
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <span class="d-sm-none d-lg-inline-block text-dark">{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt mt-2"></i>Chiqish</button>
                </form>
            </div>
        </li>
    </ul>
</nav>
