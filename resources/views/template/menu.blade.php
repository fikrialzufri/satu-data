<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="40" src="{{ asset('img/logomenu.png') }}" class="header-brand-img"
                    title="SIP PDAM Samarinda">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment1 == '' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
                @canany('view-satuan')
                    <div class="nav-lavel">{{ __('Master Data') }} </div>
                    <div class="nav-item {{ $segment1 == 'satuan' ? 'active' : '' }}">
                        <a href="{{ route('satuan.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Satuan') }}</span>
                        </a>
                    </div>


                @endcan
                @canany('view-satuan')
                    <div class="nav-lavel">{{ __('Element') }} </div>
                    <div class="nav-item {{ $segment1 == 'jenis-unit' ? 'active' : '' }}">
                        <a href="{{ route('jenis-unit.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Jenis Unit') }}</span>
                        </a>
                    </div>
                    <div class="nav-item {{ $segment1 == 'jenis-data' ? 'active' : '' }}">
                        <a href="{{ route('jenis-data.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Jenis Data') }}</span>
                        </a>
                    </div>
                    <div class="nav-item {{ $segment1 == 'unit' ? 'active' : '' }}">
                        <a href="{{ route('unit.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Unit') }}</span>
                        </a>
                    </div>
                    <div class="nav-item {{ $segment1 == 'legenda' ? 'active' : '' }}">
                        <a href="{{ route('legenda.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Legenda') }}</span>
                        </a>
                    </div>
                    <div class="nav-item {{ $segment1 == 'group' ? 'active' : '' }}">
                        <a href="{{ route('group.index') }}">
                            <i class="ik ik-box"></i>
                            <span>{{ __('Group') }}</span>
                        </a>
                    </div>


                @endcan
                @canany(['view-user', 'view-roles'])
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'user' || $segment1 == 'role' || $segment1 == 'task' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Pengguna') }}</span></a>
                        <div class="submenu-content">
                            @can('view-user')
                                <a href="{{ route('user.index') }}"
                                    class="menu-item {{ $segment1 == 'user' ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            @endcan
                            @can('view-roles')
                                <a href="{{ route('role.index') }}"
                                    class="menu-item {{ $segment1 == 'role' ? 'active' : '' }}">
                                    Hak Akses
                                </a>
                            @endcan
                            @role('superadmin')
                                <a href="{{ route('task.index') }}"
                                    class="menu-item {{ $segment1 == 'task' ? 'active' : '' }}">
                                    Task
                                </a>
                            @endrole
                        </div>
                    </div>
                @endcan
            </nav>
        </div>
    </div>
</div>
