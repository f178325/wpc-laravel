<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('getDashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets') }}/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets') }}/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <a href="{{ route('getDashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets') }}/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">MENU</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == '' ? 'active' : ''}}"
                       href="{{ route('getDashboard') }}"><i class="mdi mdi-home"></i><span>Dashboard</span></a>
                </li>
                <li class="menu-title"><span data-key="t-menu">ACCOUNTS</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'a' ? 'active' : ''}}"
                       href="#"><i class="mdi mdi-home"></i><span>Setup Cpanel Account</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) === 'servers' ? 'active' : ''}}"
                       href="{{ route('getHosts') }}"><i
                            class="mdi mdi-view-carousel-outline"></i><span>My Websites</span></a>
                </li>
                <li class="menu-title"><span data-key="t-menu">MANAGE EMAILS</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'bulk-create-emails' ? 'active' : ''}}"
                       href="{{ route('getEmails') }}"><i class="mdi mdi-email"></i><span>Create Emails</span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
