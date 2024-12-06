<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="adminLTE/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><h3>Notulen Apps</h3></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php if (isset($user) && !empty($user->user_image)): ?>
          <img src="<?= base_url('user_image/' . $user->user_image); ?>" class="card-img" alt="...">
        <?php else: ?>
          <img src="<?= base_url('user_image/default.png'); ?>" class="card-img" alt="Default Image">
        <?php endif; ?>
      </div>
      <div class="info">
    <a href="#" class="d-block"><h7><?= user()->fullname; ?></h7></a>
    <a href="#" class="d-block"><h6>@<?= user()->username; ?></h6></a>
</div>

    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-header">Home</li>
        <li class="nav-item">
          <a href="<?= base_url('dashboard'); ?>" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>


        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <!-- User Management header -->

        <?php if (in_groups('admin')): ?>
          <li class="nav-header">User Management</li>

          <!-- User List item -->
          <li class="nav-item">
            <a href="<?= base_url('admin'); ?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>User List</p>
            </a>
          </li>

          <li class="nav-header">Departemen Management</li>

          <!-- User List item -->
          <li class="nav-item">
            <a href="<?= base_url('departemen'); ?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Departemen List</p>
            </a>
          </li>
        <?php endif; ?>

        <!--Agenda List Item-->
        <li class="nav-header">Agenda Management</li>
        <?php if (in_groups('admin')): ?>

        <li class="nav-item">
          <a href="<?= base_url('agenda'); ?>" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Agenda List
            </p>
          </a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="<?=base_url('calendar');?>" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
               Calendar
            </p>
          </a>
        </li>

        <li class="nav-header">Notulen Management</li>

        <li class="nav-item">
          <a href="<?= base_url('notulen'); ?>" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
               Notulen List
            </p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>