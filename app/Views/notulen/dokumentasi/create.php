<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Dokumentasi Notulen</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Dokumentasi Notulen</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Dokumentasi Notulen</h3>
                </div>
                <form method="post" action="/notulen/dokumentasi/store" enctype="multipart/form-data">
    <div class="form-group">
        <label>Judul Agenda</label>
        <input type="text" class="form-control" value="<?= esc($notulen['judul']) ?>" readonly>
    </div>

    <div class="form-group">
        <label>Isi Notulen</label>
        <textarea class="form-control" rows="5" readonly><?= esc($notulen['isi_notulens']) ?></textarea>
    </div>

    <input type="hidden" name="notulen_id" value="<?= esc($notulen['notulen_id']) ?>">

    <div class="form-group">
        <label for="images">Upload Images</label>
        <input type="file" name="images[]" id="images" multiple class="form-control" required>
    </div>

    <div class="form-group mt-3">
    <button type="submit" class="btn btn-primary">
         Submit
    </button>
    <a href="/notulen" class="btn btn-secondary ml-2">
         Kembali
    </a>
</div>


</form>


            </div>
        </div>
    </section>
</div>

<!-- JavaScript untuk memperbarui nama file yang dipilih -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.querySelector('.custom-file-input');
        const fileLabel = document.querySelector('.custom-file-label');

        fileInput.addEventListener('change', function() {
            const fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
            fileLabel.textContent = fileNames || 'Pilih file';
        });
    });
</script>