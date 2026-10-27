<div class="vertical-menu">

    <div class="navbar-brand-box">
        <a href="{{ Auth::check() ? route('redirect') : '/' }}" class="logo logo-dark"> {{-- Link logo sesuai role user login --}}
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-dark-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logodasar.png') }}" alt="" height="28">
            </span>
        </a>

        <a href="{{ Auth::check() ? route('redirect') : '/' }}" class="logo logo-light"> {{-- Link logo sesuai role user login --}}
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="30">
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-light-sm.png') }}" alt="" height="26">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                {{-- Dashboard Utama (akan mengarahkan sesuai role) --}}
                <li class="menu-title" data-key="t-dashboard">Dashboard</li>
                <li>
                    <a href="{{ route('redirect') }}" class="{{ Request::routeIs('redirect') || Request::routeIs('admin.dashboard') || Request::routeIs('Approval.DashboardKantor') || Request::routeIs('Approval.DashboardPabrik') || Request::routeIs('Pemohon.DashboardPemohon') ? 'active waves-effect' : 'waves-effect' }}">
                        <i class="bx bx-home-circle icon nav-icon"></i> {{-- Mengganti ikon jika perlu --}}
                        <span class="menu-item" data-key="t-main-dashboard">Dashboard Utama</span>
                    </a>
                </li>


                {{-- BAGIAN MENU KHUSUS ADMIN (ROLE ID 1) --}}
                @if(Auth::check() && Auth::user()->role_id == 1)
                    <li class="menu-title" data-key="t-admin-management">Administrasi Sistem</li>

                    <li>
                        <a href="{{ route('admin.admin.dashboard') }}" class="{{ Request::routeIs('admin.dashboard') ? 'active waves-effect' : 'waves-effect' }}">
                            <i class="bx bx-building icon nav-icon"></i> {{-- Mengganti ikon sesuai kebutuhan admin --}}
                            <span class="menu-item" data-key="t-admin-dashboard">Dashboard Admin</span>
                        </a>
                    </li>

                    {{-- Link ke Manajemen Unit Kerja --}}
                    <li>
                        <a href="{{ route('admin.unit-kerja.index') }}" class="{{ Request::routeIs('admin.unit-kerja.*') ? 'active waves-effect' : 'waves-effect' }}">
                            <i class="bx bx-building-house icon nav-icon"></i> {{-- Ikon unit kerja --}}
                            <span class="menu-item" data-key="t-unit-kerja-manage">Manajemen Unit Kerja</span>
                        </a>
                    </li>

                    {{-- Link Info Area Kerja (jika ingin tampil) --}}
                    <li>
                        <a href="#" class="waves-effect"> {{-- Jika tidak ada halaman spesifik, biarkan '#' --}}
                            <i class="bx bx-buildings icon nav-icon"></i> {{-- Ikon area kerja --}}
                            <span class="menu-item" data-key="t-area-kerja-info">Info Area Kerja</span>
                        </a>
                    </li>
                @endif


                {{-- BAGIAN MENU KHUSUS APPROVAL KANTOR (VP Kamtib - ROLE ID 2) --}}
                @if(Auth::check() && Auth::user()->role_id == 2)
                    <li class="menu-title" data-key="t-approval-kamtib">Approval Kantor</li>
                    <li>
                        <a href="{{ route('Approval.DashboardKantor') }}" class="{{ Request::routeIs('Approval.DashboardKantor') || Request::routeIs('Approval.CheckSubmissionKantor') ? 'active waves-effect' : 'waves-effect' }}">
                            <i class="bx bx-file-check icon nav-icon"></i>
                            <span class="menu-item" data-key="t-kamtib-dashboard">Dashboard Approval</span>
                        </a>
                    </li>
                @endif


                {{-- BAGIAN MENU KHUSUS APPROVAL PABRIK (VP Operasi 1 - ROLE ID 3) --}}
                @if(Auth::check() && Auth::user()->role_id == 3)
                    <li class="menu-title" data-key="t-approval-operasi">Approval Pabrik</li>
                    <li>
                        <a href="{{ route('Approval.DashboardPabrik') }}" class="{{ Request::routeIs('Approval.DashboardPabrik') || Request::routeIs('Approval.CheckSubmissionPabrik') ? 'active waves-effect' : 'waves-effect' }}">
                            <i class="bx bx-factory icon nav-icon"></i>
                            <span class="menu-item" data-key="t-pabrik-dashboard">Dashboard Approval</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        </div>
</div>
