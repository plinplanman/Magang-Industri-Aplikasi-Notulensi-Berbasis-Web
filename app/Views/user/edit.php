<!-- app/Views/user/edit.php -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active">Edit User</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Edit User Details</h3>
      </div>
      <div class="card-body">
        <?= view('Myth\Auth\Views\_message_block') ?>

        <!-- Form untuk mengedit user -->
        <form action="<?= site_url('user/update/' . $user->id) ?>" method="post">
          <input type="hidden" name="_method" value="PUT">

                    <!-- Fullname Field -->
                    <div class="form-group">
            <label for="fullname">Full Name</label>
            <div class="input-group">
              <input type="fullname" name="fullname" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                placeholder="<?= lang('Auth.fullname') ?>" value="<?= old('fullname', $user->fullname) ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Email Field -->
          <div class="form-group">
            <label for="email"><?= lang('Auth.email') ?></label>
            <div class="input-group">
              <input type="email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                placeholder="<?= lang('Auth.email') ?>" value="<?= old('email', $user->email) ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Username Field -->
          <div class="form-group">
            <label for="username"><?= lang('Auth.username') ?></label>
            <div class="input-group">
              <input type="text" name="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                placeholder="<?= lang('Auth.username') ?>" value="<?= old('username', $user->username) ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Departemen Field -->
          <div class="form-group">
            <label for="departemen_id">Departemen</label>
            <select name="departemen_id" class="form-control">
              <option value="">Pilih Departemen</option>
              <?php foreach ($departemens as $departemen): ?>
                <option value="<?= $departemen['departemen_id'] ?>" <?= old('departemen_id', $user->departemen_id) == $departemen['departemen_id'] ? 'selected' : '' ?>>
                  <?= $departemen['nama_departemen'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Role Field -->
          <div class="form-group">
            <label for="role_id">Role</label>
            <select name="role_id" class="form-control" id="role_id">
              <?php foreach ($roles as $role): ?>
                <option value="<?= esc($role->id) ?>" <?= (old('role_id') ?? $user->role_id) == $role->id ? 'selected' : '' ?>>
                  <?= esc($role->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>


          <!-- Update Button -->
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Update</button>
            </div>
          </div>
        </form>


      </div>
      <div class="card-footer">
        <p class="text-center">
          <a href="<?= base_url('admin') ?>">kembali</a>
        </p>
      </div>
    </div>
  </section>
</div>