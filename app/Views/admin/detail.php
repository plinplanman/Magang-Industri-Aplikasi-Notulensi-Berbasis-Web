  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Admin Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>

          </div>
        </div>
        <div class="card-body">
          <h3> User Detail</h3>

          <div class="row">
            <div class="col-lg-8">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">

                    <!--sementara gambar-->
                    <img src="<?= base_url('user_image/default.png'); ?>" class="card-img" alt="...">
                  </div>

                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Card title</h5>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          <h4><?= $user->username; ?></h4>
                        </li>
                        <?php if ($user->fullname): ?>
                          <li class="list-group-item"><?= $user->fullname; ?></li>
                        <?php endif; ?>

                        <li class="list-group-item"><?= $user->email ?></li>
                        <li class="list-group-item">
                          <span class="badge badge-<?= ($user->name == 'pimpinan') ? 'success' : (($user->name == 'admin') ? 'primary' : 'warning'); ?>">
                            <?= $user->name; ?>
                          </span>
                        </li>
                        <li class="list-group-item">
                        <?= $user->nama_departemen; ?>
                      </li>
                        <li class="list-group-item">
                          <small> <a href="<?= base_url('admin'); ?>">&laquo;back to user list</a></small>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->