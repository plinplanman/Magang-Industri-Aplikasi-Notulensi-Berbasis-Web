<!-- app/Views/user/create.php -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Create New User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active">Create New User</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">User Registration</h3>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Create New User</p>
        <?= view('Myth\Auth\Views\_message_block') ?>

        <form action="<?= url_to('user.store') ?>" method="post">
          <?= csrf_field() ?>

          <!-- Fullname Field -->
          <div class="input-group mb-3">
            <input type="fullname" name="fullname" class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>"
              placeholder="Fullname" value="<?= old('fullname') ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>

          <!-- Email Field -->
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
              placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>

          <!-- Username Field -->
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
              placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>

          <!-- Password Field -->
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
              placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div class="input-group mb-3">
            <input type="password" name="pass_confirm" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>"
              placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <!-- Departemen Field -->
          <div class="input-group mb-3">
            <select name="departemen_id" class="form-control">
              <option value="">Pilih Departemen</option>
              <?php foreach ($departemens as $departemen): ?>
                <option value="<?= $departemen['departemen_id'] ?>" <?= old('departemen_id') == $departemen['departemen_id'] ? 'selected' : '' ?>>
                  <?= $departemen['nama_departemen'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Role Field -->
          <div class="input-group mb-3">
            <select name="role_id" class="form-control">
              <?php foreach ($roles as $role) : ?>
                <option value="<?= $role->id ?>"><?= esc($role->name) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-users"></span>
              </div>
            </div>
          </div>

          <!-- Register Button -->
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Create New User</button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <p class="text-center"><a href="<?= base_url('admin') ?>">Back</a></p>
      </div>
    </div>
    <!-- /.card -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
