<div class="content-wrapper">
    <section class="content-header">
        <h1 class="font-weight-bold text-center"><?= esc($title) ?></h1>
    </section>
    <section class="content">
        <div class="box">
            
            <div class="box-header with-border">
                <h3 class="box-title font-weight-bold">Nama Agenda</h3>
                <h4><?= esc($notulen['judul']) ?></h4>

            </div>
            <div class="box-body">
                <h3 class="font-weight-bold">Isi Notulen</h3>
                <ul>
                    <?php foreach ($notulen['isi_notulens'] as $poin): ?>
                        <?php if (trim($poin) !== ''): ?> <!-- Hanya tampilkan jika tidak kosong -->
                            <li><?= esc($poin) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <h3 class="font-weight-bold">Dokumentasi</h3>
                <?php if (!empty($dokumentasi) && count($dokumentasi) > 0): ?>
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($dokumentasi as $key => $dok): ?>
                                <?php if (!empty($dok['image'])): ?>
                                    <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                        <img src="<?= base_url('dokumentasi_notulen/' . esc($dok['image'])) ?>"
                                            class="d-block w-100 img-fluid"
                                            alt="Dokumentasi <?= $key + 1 ?>"
                                            style="max-height: 500px; object-fit: cover;">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Dokumentasi <?= $key + 1 ?></h5>
                                            <p>Foto dari kegiatan terkait</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Tidak ada dokumentasi tersedia.</p>
                <?php endif; ?>

                <div class="d-flex justify-content-center mt-3">
    <button class="btn btn-secondary" onclick="goBack()">Kembali</button>
</div>

<script>
    function goBack() {
        // Redirect ke halaman sebelumnya
        if (document.referrer) {
            window.location.href = document.referrer;
        } else {
            // Jika tidak ada referrer, arahkan ke halaman default
            window.location.href = "/";
        }
    }
</script>


            </div>
        </div>
    </section>
</div>