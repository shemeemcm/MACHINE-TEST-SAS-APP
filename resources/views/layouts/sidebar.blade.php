@php
    // Sidebar navigation for admin panel
@endphp
<div class="sidebar d-lg-block bg-dark position-fixed h-100" id="adminSidebar">
    <div class="sidebar-header p-3 text-center border-bottom border-secondary">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand text-white fw-bold" style="font-family: 'Inter', sans-serif;">
            <i class="fas fa-tools me-2"></i> Admin Panel
        </a>
    </div>
    <ul class="nav flex-column p-2">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- Organizations -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#orgMenu" role="button" aria-expanded="false" aria-controls="orgMenu">
                <i class="fas fa-building me-2"></i>
                <span>Organizations</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="orgMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.organizations.index') }}">List</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.organizations.create') }}">Add</a></li>
                </ul>
            </div>
        </li>
        <!-- Users -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#userMenu" role="button" aria-expanded="false" aria-controls="userMenu">
                <i class="fas fa-users me-2"></i>
                <span>Users</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="userMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">List</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.create') }}">Add</a></li>
                </ul>
            </div>
        </li>
        <!-- Roles & Permissions -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.roles.index') }}">
                <i class="fas fa-user-tag me-2"></i>
                <span>Roles</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.permissions.index') }}">
                <i class="fas fa-key me-2"></i>
                <span>Permissions</span>
            </a>
        </li>
        <!-- Tickets -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#ticketMenu" role="button" aria-expanded="false" aria-controls="ticketMenu">
                <i class="fas fa-ticket-alt me-2"></i>
                <span>Tickets</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="ticketMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.index', ['status' => 'open']) }}">Open</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.index', ['status' => 'closed']) }}">Closed</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.index', ['assignee' => auth()->id()]) }}">Assigned</a></li>
                </ul>
            </div>
        </li>
        <!-- Audit Logs -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.audit-logs.index') }}">
                <i class="fas fa-clipboard-list me-2"></i>
                <span>Audit Logs</span>
            </a>
        </li>
        <!-- Settings -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.settings') }}">
                <i class="fas fa-cog me-2"></i>
                <span>Settings</span>
            </a>
        </li>
        <!-- Logout -->
        <li class="nav-item mt-auto">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-white w-100 text-start d-flex align-items-center">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>
