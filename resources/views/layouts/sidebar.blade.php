<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <img alt="image" src="/assets/img/Logo-orange-background.jpg" class="header-logo" />
                <span class="logo-name">Admin panel</span>
                <p class="font-14">@role('admin') Barcha filiallar @endrole
                    @role('manager|contract')
                    @foreach(auth()->user()->regions as $region)
                        {{ $region->name_uz }}
                    @endforeach
                    @endrole
                </p>
            </a>
        </div>
        <ul class="sidebar-menu">
            @role('admin')
            <li class="dropdown @if(request()->routeIs('dashboard')) active @endif ">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i><span>Statistika</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('log*')) active @endif ">
                <a href="{{ route('log.index') }}" class="nav-link"><i class="fas fa-user-clock"></i><span>Tarix</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('message*')) active @endif ">
                <a href="{{ route('message.index') }}" class="nav-link"><i class="fab fa-facebook-messenger"></i><span>Javob xabarlari</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('start*')) active @endif ">
                <a href="{{ route('start.index') }}" class="nav-link"><i class="fab fa-telegram-plane"></i><span>Start xabarlari</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('question*')) active @endif ">
                <a href="{{ route('question.index') }}" class="nav-link"><i class="fas fa-question-circle"></i><span>Savolliklar</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('category*')) active @endif ">
                <a href="{{ route('category.index') }}" class="nav-link"><i class="fas fa-layer-group"></i><span>Kategoriyalar</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('user*')) active @endif ">
                <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-user-tie"></i><span>Foydalanuvchilar</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('region*')) active @endif ">
                <a href="{{ route('region.index') }}" class="nav-link"><i class="fas fa-map-marked-alt"></i><span>Filiallar</span></a>
            </li>
            <li class="dropdown @if(request()->routeIs('car*')) active @endif ">
                <a href="{{ route('car.index') }}" class="nav-link"><i class="fas fa-car"></i><span>Mashinalar</span></a>
            </li>
            <li class="dropdown @if(request()->is('answer/manager/*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-users"></i><span>Menedjer</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('answer.managerIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerIndex') }}">Yangi buyurtmalar</a>
                    </li>
                    <li @if(request()->routeIs('answer.managerAcceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerAcceptedIndex') }}">Qabul qilingan buyurtmalar</a>
                    </li>
                    <li @if(request()->routeIs('answer.managerCancelIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerCancelIndex') }}">Bekor qilingan buyurtmalar</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown @if(request()->is('answer/contract/*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-user-edit"></i><span>Shartomachi</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('answer.contractIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractIndex') }}">Yangi buyurtmalar</a>
                    </li>
                    <li @if(request()->routeIs('answer.contractAcceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractAcceptedIndex') }}">Qabul qilingan buyurtmalar</a>
                    </li>
                    <li @if(request()->routeIs('answer.contractCancelIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractCancelIndex') }}">Bekor qilingan buyurtmalar</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown @if(request()->routeIs('ticket*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-clipboard-list"></i><span>Ticketlar</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('ticket.index')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.index') }}">Yangi</a>
                    </li>
                    <li @if(request()->routeIs('ticket.acceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.acceptedIndex') }}">Qabul qilingan</a>
                    </li>
                    <li @if(request()->routeIs('ticket.closedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.closedIndex') }}">Tugallangan</a>
                    </li>
                    <li @if(request()->routeIs('ticket.trashIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.trashIndex') }}">O'chirilgan</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown @if(request()->routeIs('supplier-assignment*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-adjust"></i><span>Qoshimcha topshiriqlar</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('supplier-assignment.index')) class="active" @endif>
                        <a class="nav-link" href="{{ route('supplier-assignment.index') }}">Yangi</a>
                    </li>
                    <li @if(request()->routeIs('supplier-assignment.closedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('supplier-assignment.closedIndex') }}">Tugallangan</a>
                    </li>
                    <li @if(request()->routeIs('supplier-assignment.trashed')) class="active" @endif>
                        <a class="nav-link" href="{{ route('supplier-assignment.trashed') }}">O'chirilgan</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown @if(request()->routeIs('additional-notice*')) active @endif ">
                <a href="{{ route('additional-notice.index') }}" class="nav-link"><i class="fas fa-bell"></i><span>Bildirishnomalar</span></a>
            </li>
            @endrole

            @role('manager')
            <li class="dropdown @if(request()->is('answer/manager/*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-users"></i><span>Menedjer</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('answer.managerIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerIndex') }}">Yangi</a>
                    </li>
                    <li @if(request()->routeIs('answer.managerAcceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerAcceptedIndex') }}">Qabul qilingan</a>
                    </li>
                    <li @if(request()->routeIs('answer.managerCancelIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.managerCancelIndex') }}">Bekor qilingan</a>
                    </li>
                </ul>
            </li>
            @endrole

            @role('contract')
            <li class="dropdown @if(request()->is('answer/contract/*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-user-edit"></i><span>Shartnomachi</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('answer.contractIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractIndex') }}">Yangi</a>
                    </li>
                    <li @if(request()->routeIs('answer.contractAcceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractAcceptedIndex') }}">Qabul qilingan</a>
                    </li>
                    <li @if(request()->routeIs('answer.contractCancelIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('answer.contractCancelIndex') }}">Bekor qilingan</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown @if(request()->routeIs('ticket*')) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-clipboard-list"></i><span>Ticketlar</span></a>
                <ul class="dropdown-menu">
                    <li @if(request()->routeIs('ticket.index')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.index') }}">Yangi</a>
                    </li>
                    <li @if(request()->routeIs('ticket.acceptedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.acceptedIndex') }}">Qabul qilingan</a>
                    </li>
                    <li @if(request()->routeIs('ticket.closedIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.closedIndex') }}">Tugallangan</a>
                    </li>
                    <li @if(request()->routeIs('ticket.trashIndex')) class="active" @endif>
                        <a class="nav-link" href="{{ route('ticket.trashIndex') }}">O'chirilgan</a>
                    </li>
                </ul>
            </li>
            @endrole
        </ul>
    </aside>
</div>
