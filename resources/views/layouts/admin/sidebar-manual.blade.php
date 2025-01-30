<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/favicon-admin.png') }}" alt="logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <!-- DATA-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="News">DATA</span>
        </li>

        <!-- Role -->
        <li class="menu-item {{ request()->routeIs('admin.role*') ? 'active' : '' }}">
            <a href="{{ route('admin.role.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Role">Role</div>
            </a>
        </li>
        <!-- Departemen -->
        <li class="menu-item {{ request()->routeIs('admin.departemen*') ? 'active' : '' }}">
            <a href="{{ route('admin.departemen.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Departemen">Departemen</div>
            </a>
        </li>

        <!-- ABSENSI-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Absensi">Absensi</span>
        </li>

        <!-- Semua -->
        <li class="menu-item {{ request()->routeIs('admin.dosen*') ? 'active' : '' }}">
            <a href="{{ route('admin.departemen.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Semua">Semua</div>
            </a>
        </li>
        @foreach (\Helper::getDepartemen() as $item)
            <!-- {{ $item->nama }} -->
            <li class="menu-item {{ request()->routeIs('admin.dosen*') ? 'active' : '' }}">
                <a href="{{ route('admin.departemen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-mail"></i>
                    <div data-i18n="{{ $item->nama }}">{{ $item->nama }}</div>
                </a>
            </li>
        @endforeach

        <!-- ADMINISTRATOR-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Administrator">ADMINISTRATOR</span>
        </li>

        <!-- Users -->
        <li class="menu-item {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
            <a href="{{ route('admin.user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
        <!-- Profile -->
        <li class="menu-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <a href="{{ route('admin.profile.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Profile">Profile</div>
            </a>
        </li>
    </ul>
</aside>
