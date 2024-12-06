<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?= $title; ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active"><?= $title; ?></li>
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
        <h3 class="card-title">Form Tambah Personil Meeting</h3>
      </div>
      <div class="card-body">
        <form action="/agenda/personil/store" method="post">
          <?= csrf_field() ?>

          <div class="form-group">
            <label for="agenda_id">Agenda</label>
            <input type="hidden" name="agenda_id" id="agenda_id" value="<?= $agenda_id; ?>">
            <input type="text" class="form-control" value="<?= $agenda_title; ?>" readonly>
          </div>

          <!-- Pilih personil untuk Ditambahkan -->
          <div class="form-group">
            <label for="user_select">Tambah personil</label>
            <select id="user_select" class="form-control">
    <option value="">Pilih personil</option>
    <?php foreach ($users as $user): ?>
        <option value="<?= $user->id; ?>"><?= $user->fullname; ?> (<?= $user->departemen; ?> - <?= $user->group; ?>)</option>
    <?php endforeach; ?>
</select>

          </div>

          <!-- Tabel personil yang Dipilih -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>personil</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="selected_users">
              <!-- Baris personil yang dipilih akan muncul di sini -->
            </tbody>
          </table>

          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="/agenda" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        Isi form untuk menambahkan Personil baru.
      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
document.addEventListener("DOMContentLoaded", function() {
  const userSelect = document.getElementById("user_select");
  const selectedUsersTable = document.getElementById("selected_users");

  userSelect.addEventListener("change", function() {
    const userId = this.value;
    const username = this.options[this.selectedIndex].text;

    // Cek apakah personil sudah ditambahkan
    if (!userId || selectedUsersTable.querySelector(`[data-user-id="${userId}"]`)) return;

    // Tambahkan baris personil yang dipilih ke tabel
    const row = document.createElement("tr");
    row.dataset.userId = userId;
    row.innerHTML = `
      <td>${username}</td>
      <td>
        <button type="button" class="btn btn-sm btn-danger remove-user">Hapus</button>
        <input type="hidden" name="user_id[]" value="${userId}">
      </td>
    `;

    selectedUsersTable.appendChild(row);
  });

  // Hapus baris personil yang dipilih pada klik tombol hapus
  selectedUsersTable.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-user")) {
      e.target.closest("tr").remove();
    }
  });
});
</script>
