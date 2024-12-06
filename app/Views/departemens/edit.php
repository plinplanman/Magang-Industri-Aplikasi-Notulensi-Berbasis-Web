<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Departemen</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Departemen</li>
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
        <h3 class="card-title">Form Edit Departemen</h3>
      </div>
      <div class="card-body">
        <form action="/departemens/update/<?= $departemen['departemen_id'] ?>" method="post">
          <div class="form-group">
            <label for="nama_departemen">Nama Departemen</label>
            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" value="<?= $departemen['nama_departemen'] ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="/departemens" class="btn btn-secondary">Batal</a>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        Edit data departemen.
      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
