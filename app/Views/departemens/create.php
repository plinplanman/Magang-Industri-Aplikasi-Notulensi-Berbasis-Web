<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?= $title;?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Tambah Departemen</li>
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
        <h3 class="card-title">Form Tambah Departemen</h3>
      </div>
      <div class="card-body">
        <form action="/departemens/store" method="post">
          <div class="form-group">
            <label for="nama_departemen">Nama Departemen</label>
            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="/departemens" class="btn btn-secondary">Batal</a>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        Isi form untuk menambahkan departemen baru.
      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
