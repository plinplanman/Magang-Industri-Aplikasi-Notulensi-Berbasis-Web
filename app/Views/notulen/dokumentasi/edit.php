<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Dokumentasi Notulen</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/notulen">Home</a></li>
                        <li class="breadcrumb-item active">Edit Dokumentasi Notulen</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Dokumentasi</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="judul">Judul Agenda</label>
                        <input type="text" class="form-control" id="judul" value="<?= $notulen['judul'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="isi_notulens">Isi Notulens</label>
                        <textarea class="form-control" id="isi_notulens" rows="4" readonly><?= $notulen['isi_notulens'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="currentImages">Gambar Saat Ini</label>
                        <div class="row">
                            <?php foreach ($dokumentasi as $gambar): ?>
                                <div class="col-md-3">
                                    <div class="card">
                                        <img src="<?= base_url('/dokumentasi_notulen/' . $gambar['image']) ?>" 
                                             alt="Image"
                                             class="card-img-top img-thumbnail"
                                             style="max-height: 200px;">
                                        <div class="card-body text-center">
                                            <a href="<?= base_url('/notulen/dokumentasi/delete/' . $gambar['id']) ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">
                                               Hapus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="<?= base_url('/notulen/dokumentasi/create/' . $notulen['notulen_id']) ?>" 
                           class="btn btn-success">
                           Tambah Gambar
                        </a>
                        <a href="/notulen" class="btn btn-secondary">Kembali</a>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
