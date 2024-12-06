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
                        <li class="breadcrumb-item"><a href="#">Agenda Management</a></li>
                        <li class="breadcrumb-item active"><?= $title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <a href="/agenda/create" class="btn btn-primary btn-sm">Tambah Agenda</a>
                        <a href="/notifikasi/kirimReminder" class="btn btn-primary btn-sm">Kirim Reminder</a>
                        <?php if (session()->getFlashdata('success')) : ?>
                            <script type="text/javascript">
                                alert("<?= session()->getFlashdata('success'); ?>");
                            </script>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')) : ?>
                            <script type="text/javascript">
                                alert("<?= session()->getFlashdata('error'); ?>");
                            </script>
                        <?php endif; ?>

                    </div>
                    <div class="card-body">
                        <form method="get" action="<?= base_url('agenda'); ?>" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Judul, Nama Pelanggan, Lokasi..." value="<?= esc($search); ?>">
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const startDateInput = document.querySelector('input[name="start_date"]');
                                const endDateInput = document.querySelector('input[name="end_date"]');
                                const form = document.querySelector('form');

                                form.addEventListener('submit', function(event) {
                                    const startDate = startDateInput.value;
                                    const endDate = endDateInput.value;

                                    // Validasi: Pastikan tanggal akhir tidak lebih awal dari tanggal mulai
                                    if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                                        alert('Tanggal akhir tidak boleh lebih awal dari tanggal mulai.');
                                        event.preventDefault(); // Cegah pengiriman formulir
                                    }
                                });

                                // Tambahkan perubahan dinamis untuk memastikan pengguna tidak salah pilih
                                startDateInput.addEventListener('change', function() {
                                    if (startDateInput.value && new Date(startDateInput.value) > new Date(endDateInput.value)) {
                                        endDateInput.value = ''; // Kosongkan input tanggal akhir jika tidak valid
                                    }
                                    endDateInput.min = startDateInput.value; // Atur batas minimum untuk tanggal akhir
                                });

                                endDateInput.addEventListener('change', function() {
                                    if (endDateInput.value && new Date(endDateInput.value) < new Date(startDateInput.value)) {
                                        startDateInput.value = ''; // Kosongkan input tanggal mulai jika tidak valid
                                    }
                                    startDateInput.max = endDateInput.value; // Atur batas maksimum untuk tanggal mulai
                                });
                            });
                        </script>

                        <!-- Tabel Agenda -->
                        <table id="agendaTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Lokasi</th>
                                    <th>Personil Meeting</th>
                                    <th>Link(Opsional)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agendas as $agenda): ?>
                                    <tr>
                                        <td><?= esc($agenda['judul']) ?></td>
                                        <td><?= esc($agenda['tanggal_kegiatan']) ?></td>
                                        <td><?= esc($agenda['jam_kegiatan']) ?></td>
                                        <td><?= esc($agenda['nama_pelanggan']) ?></td>
                                        <td><?= esc($agenda['lokasi']) ?></td>
                                        <td>
                                            <?php if (empty($agenda['users'])): ?>
                                                <!-- Tampilkan tombol Tambah Personil jika belum ada personel -->
                                                <a href="/agenda/personil/create?agenda_id=<?= esc($agenda['id']); ?>" class="btn btn-primary btn-sm">Tambah Personil</a>
                                            <?php else: ?>
                                                <!-- Tampilkan tombol Edit jika personel sudah ada -->
                                                <a href="/agenda/personil/edit/<?= esc($agenda['id']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                            <?php endif; ?>
                                        </td>


                                        <td>
                                            <?php if ($agenda['link']): ?>
                                                <a href="<?= esc($agenda['link']) ?>" target="_blank">Link Vicon</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/agenda/detail/<?= esc($agenda['id']) ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                            <a href="/agenda/edit/<?= esc($agenda['id']) ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="/agenda/delete/<?= esc($agenda['id']) ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div style="float: right">
                            <?= $pager->links('agendas', 'custom_pager') ?>
                        </div>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
        $('#agendaTable').DataTable({
            "paging": true, // Enable pagination
            "searching": true, // Enable search
            "lengthChange": true, // Enable changing number of items per page
            "responsive": true, // Enable responsiveness
            "autoWidth": false, // Automatically adjust column widths
        });
    });
</script>