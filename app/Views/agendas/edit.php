<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Agenda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Agenda</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Agenda</h3>
            </div>
            <form action="/agenda/update/<?= $agenda['id'] ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="judul">Judul:</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="<?= $agenda['judul'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan:</label>
                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= $agenda['nama_pelanggan'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi:</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= $agenda['lokasi'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                        <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?= $agenda['tanggal_kegiatan'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_kegiatan">Jam Kegiatan:</label>
                        <input type="time" class="form-control" id="jam_kegiatan" name="jam_kegiatan" value="<?= $agenda['jam_kegiatan'] ?>" min="08:00" max="16:00" required>
                    </div>
                    <div class="form-group">
                        <label for="lampiran">Lampiran:</label>
                        <input type="file" class="form-control-file" id="lampiran" name="lampiran" accept=".jpg,.png,">
                        <?php if ($agenda['lampiran']): ?>
                            <small>File saat ini: <?= $agenda['lampiran'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="link">Link (Opsional):</label>
                        <input type="url" class="form-control" id="link" name="link" value="<?= $agenda['link'] ?>">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('agenda'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
</div>


