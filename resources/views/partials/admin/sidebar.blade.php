<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="ml-5 pt-1">
        @role('superadmin')
            <em>Super Admin</em>
        @endrole
        @role('admin')
            <em>Administrator</em>
        @endrole
        @role('site lead')
            <em>Site Lead</em>
        @endrole
        @role('team lead')
            <em>Team Lead</em>
        @endrole
        @role('encoder')
            <em>Encoder</em>
        @endrole
    </div>
    <ul class="nav">
      <li class="nav-item" >
        <a class="nav-link" href="{{ route('dashboard') }}">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      @hasanyrole('admin|superadmin')
          <li class="nav-item" >
            <a class="nav-link" href="{{ route('users') }}">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
          @hasanyrole('superadmin')
          <li class="nav-item" >
            <a class="nav-link" href="{{ route('roles') }}">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Roles</span>
            </a>
          </li>
          <li class="nav-item" >
            <a class="nav-link" href="{{ route('permissions') }}">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Permissions</span>
            </a>
          </li>
          @endhasanyrole
      @endhasanyrole
      @hasanyrole('admin|site lead|team lead|encoder|superadmin')
          <li class="nav-item" >
            <a class="nav-link" href="{{ route('registrants') }}">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Registrants</span>
            </a>
          </li>
      @endhasanyrole
    </ul>
  </nav>