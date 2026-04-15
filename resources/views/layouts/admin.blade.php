<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin Dashboard')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dashboard/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">


    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/panel') }}" class="brand-link">
      <span class="brand-text font-weight-bold d-block">Control Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=random" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name ?? 'Admin' }}</a>
        </div>
      </div>



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if(auth()->user()->status == 1)
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="{{ url('/panel') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- View Front Page -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener noreferrer">
              <i class="nav-icon fas fa-globe"></i>
              <p>View Front Page</p>
            </a>
          </li>

          <!-- Events -->
          <li class="nav-item">
              <a href="{{ route('admin.events.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Events</p>
            </a>
          </li>

          @if(auth()->user()->role == 1)
          <!-- Locations -->
          <li class="nav-item">
            <a href="{{ url('/locations') }}" class="nav-link">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>Locations</p>
            </a>
          </li>
          @endif

          <!-- Schedules -->
          <li class="nav-item">
            <a href="{{ url('/schedules') }}" class="nav-link">
              <i class="nav-icon fas fa-clock"></i>
              <p>Schedules</p>
            </a>
          </li>

          @if(auth()->user()->role == 1)
          <!-- Categories -->
          <li class="nav-item">
            <a href="{{ url('/categories') }}" class="nav-link">
              <i class="nav-icon fas fa-tags"></i>
              <p>Categories</p>
            </a>
          </li>
          @endif

          <!-- Ticket Types -->
            <li class="nav-item">
                <a href="{{ url('/ticket-types') }}" class="nav-link">
                    <i class="nav-icon fas fa-ticket-alt"></i>
                    <p>Ticket Types</p>
                </a>
            </li>

            @if(auth()->user()->role == 1)
            <li class="nav-item">
                <a href="{{ route('admin.waiting-list.index') }}" class="nav-link {{ Request::is('admin/waiting-list') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Waiting List</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Users</p>
                </a>
            </li>
            @endif

            @else
                <!-- Reactivate Account -->
                <li class="nav-item">
                    <a href="{{ url('/reactivate') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Reactivate Account</p>
                    </a>
                </li>
            @endif

            <li class="nav-item mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-left w-100">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </button>
                </form>
            </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->

      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title', 'Admin Dashboard')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
       @yield('content')
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset ('dashboard/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset ('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset ('dashboard/dist/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

</body>
</html>
