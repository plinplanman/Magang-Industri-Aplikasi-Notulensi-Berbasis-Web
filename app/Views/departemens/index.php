<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Daftar Departemen</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Departemen Management</a></li>
                        <li class="breadcrumb-item active"> <?= $title;?></li>
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

                        <a href="/departemens/create" class="btn btn-primary btn-sm">Tambah Departemen</a>
                    </div>
                    <div class="card-body">
                        <!-- Search Bar -->
                        <form method="get" action="/departemens" class="d-flex mb-3 align-items-center">
                            <div class="search-wrapper">
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari Departemen..." value="<?= esc($search) ?>">
                            </div>
                            <button class="btn btn-primary ms-2" type="submit">Cari</button>
                        </form>


                        <!-- Tabel Departemen -->
                        <table id="departemenTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Departemen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="departemenBody">
                                <?php foreach ($departemens as $departemen): ?>
                                    <tr>
                                        <td><?= esc($departemen['departemen_id']) ?></td>
                                        <td><?= esc($departemen['nama_departemen']) ?></td>
                                        <td>
                                            <a href="/departemens/edit/<?= esc($departemen['departemen_id']) ?>" class="btn btn-warning btn-sm" title="Edit" ><i class="fas fa-edit"></i></a>
                                            <a href="/departemens/delete/<?= esc($departemen['departemen_id']) ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus?')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div style="float: right">
                            <?php echo $pager->links('departemens', 'custom_pager') ?>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>

    <!-- JavaScript untuk Pencarian -->
    <script>
        // Ambil input pencarian dan tabel
        const searchInput = document.getElementById("searchInput");
        const tableRows = document.querySelectorAll("#departemenTable tbody tr");

        // Event listener untuk input pencarian
        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                let rowText = '';

                // Ambil teks dari semua kolom dalam baris
                row.querySelectorAll('td').forEach(cell => {
                    rowText += cell.textContent.toLowerCase() + ' '; // Gabungkan teks dari semua kolom
                });

                // Jika rowText mengandung query pencarian, tampilkan baris; jika tidak, sembunyikan
                if (rowText.includes(query)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>


</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#departemenTable').DataTable({
            "paging": true, // Enable pagination
            "searching": true, // Enable search
            "lengthChange": true, // Enable changing number of items per page
            "responsive": true, // Enable responsiveness
            "autoWidth": false, // Automatically adjust column widths
        });
    });
</script>