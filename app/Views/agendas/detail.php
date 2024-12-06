<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Agenda Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Agenda Detail</li>
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
                <h3 class="card-title"><?= $title; ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <h3> Agenda Detail</h3>
                <div class="card mb-3 w-100">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <!-- Placeholder gambar lampiran jika ada -->
                            <img src="<?= base_url('agenda_images/' . $agenda['lampiran']); ?>" class="card-img" alt="Lampiran">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Nama Agenda:</strong> <?= $agenda['judul']; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Nama Pelanggan:</strong> <?= $agenda['nama_pelanggan']; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Lokasi:</strong> <?= $agenda['lokasi']; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Tanggal Kegiatan:</strong> <?= $agenda['tanggal_kegiatan']; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Jam Kegiatan:</strong> <?= $agenda['jam_kegiatan']; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Personil Meeting:</strong>
                                        <?php if (empty($agenda['users'])): ?>
                                            <!-- Tampilkan tombol Tambah Personil jika belum ada personil -->
                                            <a href="/agenda/personil/create?agenda_id=<?= esc($agenda['id']); ?>" class="btn btn-primary btn-sm">Tambah Personil</a>
                                        <?php else: ?>
                                            <ul class="pl-3">
                                                <?php foreach ($agenda['users'] as $user): ?>
                                                    <li><?= esc($user->fullname); ?> (<?= esc($user->nama_departemen); ?> - <?= esc($user->name); ?>)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <!-- Tampilkan tombol Edit jika personil sudah ada -->
                                            <a href="/agenda/personil/edit/<?= esc($agenda['id']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                        <?php endif; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Link (Opsional):</strong> <a href="<?= $agenda['link']; ?>"><?= $agenda['link']; ?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <small>
                                            <a href="javascript:void(0);" onclick="window.history.back();">&laquo; Kembali </a>
                                        </small>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                Footer
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->