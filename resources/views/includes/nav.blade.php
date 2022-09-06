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
                <li class="menu-title"><span>INSTALLATION OPTION</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->segment(1)==='functions'?'active':'' }}"
                       href="#installation" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="installation">
                        <i class="mdi mdi-server-network"></i><span>Installation Options</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->segment(1)==='functions'?'show':'' }}"
                         id="installation">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('getBackup') }}"
                                   class="nav-link {{ request()->segment(2)==='domain-backup'?'active':'' }}">
                                    Domain Backup</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('getReconcile') }}"
                                   class="nav-link {{ request()->segment(2)==='reconcile-db'?'active':'' }}">
                                    Reconcile Database</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><span>TERMINATION</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'terminate' ? 'active' : ''}}"
                       href="{{ route('getTerminate') }}"><i
                            class="mdi mdi-delete-empty"></i><span>Account Termination</span></a>
                </li>
                <li class="menu-title"><span>REPOSITORY</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'file-repository' ? 'active' : ''}}"
                       href="{{ route('getRepository') }}"><i
                            class="mdi mdi-folder"></i><span>File Repository</span></a>
                </li>
                <li class="menu-title"><span data-key="t-menu">MANAGE EMAILS</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'bulk-create-emails' ? 'active' : ''}}"
                       href="{{ route('getEmails') }}"><i class="mdi mdi-email"></i><span>Create Emails</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->segment(1) == 'create-forwarder' ? 'active' : ''}}"
                       href="{{ route('getEmailF') }}"><i
                            class="mdi mdi-email-multiple"></i><span>Create Forwarder</span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
