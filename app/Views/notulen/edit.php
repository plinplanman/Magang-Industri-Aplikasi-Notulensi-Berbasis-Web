<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Notulen</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Notulen</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Notulen</h3>
                </div>
                <form action="/notulen/update/<?= $notulen['id']; ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <!-- Pilih Agenda -->
                        <div class="form-group">
                            <label for="agenda_id">Agenda</label>
                            <select name="agenda_id" class="form-control" required>
                                <option value="">Pilih Agenda</option>
                                <?php foreach ($agendas as $agenda): ?>
                                    <option 
                                        value="<?= $agenda['id']; ?>" 
                                        <?= $agenda['id'] == $notulen['agenda_id'] ? 'selected' : ''; ?>
                                        <?= in_array($agenda['id'], $agendaIdsWithNotulen) && $agenda['id'] != $notulen['agenda_id'] ? 'disabled' : ''; ?>>
                                        <?= $agenda['judul']; ?>
                                        <?= in_array($agenda['id'], $agendaIdsWithNotulen) && $agenda['id'] != $notulen['agenda_id'] ? '(Notulen sudah dibuat)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Input Isi Notulen -->
                        <div class="form-group">
                            <label for="isi_notulens">Isi Notulen</label>
                            <div id="notulens-list">
                                <ul id="notulens">
                                    <?php 
                                    // Pisahkan isi notulen berdasarkan baris
                                    $notulenItems = explode("\n", $notulen['isi_notulens']);
                                    foreach ($notulenItems as $item): ?>
                                        <li class="d-flex mb-2">
                                            <input 
                                                type="text" 
                                                name="isi_notulens[]" 
                                                class="form-control" 
                                                value="<?= trim($item); ?>" 
                                                placeholder="Isi poin notulen" 
                                                required 
                                            />
                                            <button type="button" class="btn btn-danger btn-sm ml-2 remove-point">Hapus</button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn btn-success btn-sm mt-2" id="add-point">Tambah Poin</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/notulen" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Tambahkan Script JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notulensList = document.getElementById('notulens');
        const addPointButton = document.getElementById('add-point');

        // Tambahkan poin baru
        addPointButton.addEventListener('click', function () {
            const newPoint = document.createElement('li');
            newPoint.classList.add('d-flex', 'mb-2');
            newPoint.innerHTML = `
                <input type="text" name="isi_notulens[]" class="form-control" placeholder="Isi poin notulen" required />
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-point">Hapus</button>
            `;
            notulensList.appendChild(newPoint);
        });

        // Hapus poin tertentu
        notulensList.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-point')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
