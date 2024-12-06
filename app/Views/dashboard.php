<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?= esc($title); ?></h1>
          <!-- Periksa jika user memiliki role admin -->
          <?php if (in_groups('admin')): ?>
            <div class="card-tools">
              <select id="filterAgenda" class="form-control">
                <option value="all" <?= ($filter === 'all') ? 'selected' : '' ?>>Lihat Semua</option>
                <option value="mine" <?= ($filter === 'mine') ? 'selected' : '' ?>>Dashboard Saya</option>
              </select>
            </div>

            <script>
              // Event listener untuk dropdown filter
              document.getElementById('filterAgenda').addEventListener('change', function() {
                const selectedFilter = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('filter', selectedFilter); // Tambahkan filter ke URL
                window.location.href = url.toString(); // Redirect ke URL baru
              });
            </script>
          <?php endif; ?>

        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><?= esc($title); ?></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Rekap Agenda Bulan Ini -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Rekap Agenda Bulan Ini</h3>
        <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
      </div>
      
      <div class="card-body">
        <?php if (!empty($thisMonthAgendas)) : ?>
          <table id="thisMonthTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Jam</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($thisMonthAgendas as $key => $agenda): ?>
                <tr>
                  <td><?= $key + 1; ?></td>
                  <td><?= esc($agenda['judul']); ?></td>
                  <td><?= date('d-m-Y', strtotime($agenda['tanggal_kegiatan'])); ?></td>
                  <td><?= esc($agenda['lokasi']); ?></td>
                  <td><?= date('H:i', strtotime($agenda['jam_kegiatan'])); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p class="text-center text-muted">Tidak ada agenda bulan ini.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Agenda Terdekat -->
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Agenda Terdekat</h3>
        <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
      </div>
      <div class="card-body">
        <?php if (!empty($upcomingAgendas)) : ?>
          <table id="upcomingTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Lokasi</th>
                <th>Link</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($upcomingAgendas as $key => $agenda): ?>
                <tr>
                  <td><?= $key + 1; ?></td>
                  <td><?= esc($agenda['judul']); ?></td>
                  <td><?= date('d-m-Y', strtotime($agenda['tanggal_kegiatan'])); ?></td>
                  <td><?= date('H:i', strtotime($agenda['jam_kegiatan'])); ?></td>
                  <td><?= esc($agenda['lokasi']); ?></td>
                  <td>
                    <?php if (!empty($agenda['link'])): ?>
                      <a href="<?= esc($agenda['link']); ?>" target="_blank" class="btn btn-sm btn-info">Lihat Link</a>
                    <?php else: ?>
                      <span class="text-muted">Tidak tersedia</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p class="text-center text-muted">Tidak ada agenda terdekat.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Agenda Yang Sudah Selesai -->
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Agenda Yang Sudah Selesai</h3>
        <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
      </div>
      <div class="card-body">
        <?php if (!empty($completedAgendas)) : ?>
          <table id="completedTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Notulen</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($completedAgendas as $key => $agenda): ?>
                <tr>
                  <td><?= $key + 1; ?></td>
                  <td><?= esc($agenda['judul']); ?></td>
                  <td><?= date('d-m-Y', strtotime($agenda['tanggal_kegiatan'])); ?></td>
                  <td>
                    <?php if (!empty($agenda['notulen_id'])): ?>
                      <a href="/notulen/detail/<?= esc($agenda['notulen_id']); ?>" class="btn btn-primary btn-sm">Lihat Notulen</a>
                    <?php else: ?>
                      <span class="text-muted">Tidak tersedia</span>
                    <?php endif; ?>
                  </td>


                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p class="text-center text-muted">Tidak ada agenda yang sudah selesai.</p>
        <?php endif; ?>
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
    $('#thisMonthTable, #upcomingTable, #completedTable').DataTable({
      "paging": true,
      "searching": true,
      "lengthChange": true,
      "responsive": true,
      "autoWidth": false,
      "language": {
        "emptyTable": "Tidak ada data tersedia."
      }
    });
  });
</script>