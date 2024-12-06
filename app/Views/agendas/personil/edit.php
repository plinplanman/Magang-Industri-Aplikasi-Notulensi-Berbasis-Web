<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Personil Meeting</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Personil</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Form Edit Personil Meeting</h3>
      </div>
      <div class="card-body">
        <form action="/agenda/personil/update/<?= $agenda_id; ?>" method="post">
          <?= csrf_field() ?>

          <!-- Pilih personil Tambahan -->
          <div class="form-group">
            <label for="user_id">Tambah personil</label><br>
            <select id="user_select" class="form-control">
              <option value="">Pilih personil</option>
              <?php foreach ($users as $user): ?>
                <option value="<?= $user->id; ?>"><?= $user->fullname; ?> (<?= $user->departemen; ?> - <?= $user->group; ?>)</option>
                <?php endforeach; ?>
            </select>
          </div><br>
          <!-- Tabel personil yang Sudah Terdaftar dalam Agenda -->
          <table class="table table-bordered">
          <label for="user_id">Data Personil Tidak Boleh Kosong</label>

            <thead>
              <tr>
                <th>personil</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="selected_users">
        <?php foreach ($agendaUsers as $agendaUser): ?>
            <?php 
                // Cari pengguna berdasarkan user_id dalam daftar users
                $userIndex = array_search($agendaUser['user_id'], array_column($users, 'id'));
                if ($userIndex !== false) {
                    $user = $users[$userIndex];
                }
            ?>
            <tr data-user-id="<?= $agendaUser['user_id'] ?>">
                <td>
                    <?= $user->fullname; ?> 
                    (<?= $user->departemen; ?> - <?= $user->group; ?>)
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-user">Hapus</button>
                    <input type="hidden" name="user_id[]" value="<?= $agendaUser['user_id']; ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
          </table>

          <button type="submit" class="btn btn-primary">Update</button>
          <a href="/agenda" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
      <div class="card-footer">
        Edit data personil untuk agenda yang dipilih.
      </div>
    </div>
  </section>
</div>

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
