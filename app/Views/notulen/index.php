<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="/notulen/create" class="btn btn-primary btn-sm">Tambah Notulen</a>
                    </div>
                    <div class="card-body">
                        <table id="notulenTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Agenda</th>
                                    <th>Notulen</th>
                                    <th>Tanda Tangan</th>
                                    <th>Dokumentasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notulens as $notulen): ?>
                                    <tr>
                                        <td><?= $notulen['id']; ?></td>
                                        <td><?= $notulen['judul']; ?></td> <!-- Menggunakan judul dari tabel agendas -->
                                        <td>
                                            <?php
                                            // Memecah isi notulen berdasarkan baris/poin
                                            $poinNotulens = explode("\n", $notulen['isi_notulens']);
                                            ?>
                                            <ul>
                                                <?php foreach ($poinNotulens as $poin): ?>
                                                    <li><?= htmlspecialchars(trim($poin)); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="/notulen/tandatangan/create/<?= $notulen['id']; ?>" class="btn btn-primary btn-sm">
                                                 <i class="fas fa-edit"></i>
                                            </a>


                                        </td>
                                        <td>
                                            <a href="<?= base_url('notulen/dokumentasi/create/' . $notulen['id']); ?>" class="btn btn-primary btn-sm">
                                                Tambah
                                            </a>
                                            <a href="<?= base_url('notulen/dokumentasi/edit/' . $notulen['id']); ?>" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                        </td>

                                        <td>
                                            <a href="/notulen/detail/<?= $notulen['id']; ?>" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/notulen/edit/<?= $notulen['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/notulen/delete/<?= $notulen['id']; ?>"
                                                class="btn btn-danger btn-sm delete-notulen"
                                                data-id="<?= $notulen['id']; ?>"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#notulenTable').DataTable({
            responsive: true,
            autoWidth: false,
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.delete-notulen');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pengalihan langsung

                const notulenId = this.getAttribute('data-id');
                const confirmation = confirm("Apakah Anda yakin ingin menghapus notulen ini?");

                if (confirmation) {
                    // Tampilkan alert saat notulen dihapus
                    alert("Notulen berhasil dihapus.");
                    window.location.href = `/notulen/delete/${notulenId}`;
                }
            });
        });
    });
</script>