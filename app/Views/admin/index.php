<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <h1><?= esc($title); ?></h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>">User Management</a></li>
            <li class="breadcrumb-item active"><?= esc($title); ?></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <a href="<?= base_url('user/create'); ?>" class="btn btn-primary btn-sm">Tambah User</a>
          </div>
          <div class="card-body">
            <div class="container mt-3">
              <!-- Alert Message -->
              <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" role="alert">
                  <?= session()->getFlashdata('success'); ?>
                </div>
              <?php elseif (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                  <?= session()->getFlashdata('error'); ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Form Pencarian -->
            <form method="get" action="<?= base_url('admin/index'); ?>" class="mb-3">
              <div class="input-group ">
                <input type="text" name="search" class="form-control" placeholder="Cari Username, Email, Fullname, atau Role" value="<?= esc($search); ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">Cari</button>
                </div>
              </div>
            </form>

            <!-- Tabel User dengan DataTables -->
            <div class="table-responsive">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Departemen</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= esc($user->username); ?></td>
                      <td><?= esc($user->email); ?></td>
                      <td><?= esc($user->name); ?></td>
                      <td><?= esc($user->nama_departemen); ?></td>
                      <td>
                        <a href="<?= base_url('admin/detail/' . $user->userid); ?>" class="btn btn-info btn-sm" title="Detail">
                          <i class="fas fa-info-circle"></i>
                        </a>
                        <a href="<?= base_url('user/edit/' . $user->userid); ?>" class="btn btn-warning btn-sm" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= base_url('user/delete/' . $user->userid); ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');" style="display:inline;">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
              <?= $pager->links('users', 'custom_pager'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    $('#userTable').DataTable({
      "paging": true,
      "searching": true,
      "lengthChange": true,
      "responsive": true,
      "autoWidth": false,
    });
  });
</script>
