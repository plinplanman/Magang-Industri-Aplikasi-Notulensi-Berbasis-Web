<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tanda Tangan</title>
    <!-- Tambahkan CSS AdminLTE -->
    <link rel="stylesheet" href="/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/adminlte.min.css">
    <script>
        let isDrawing = false;

        function startDrawing(e) {
            const canvas = e.target;
            const ctx = canvas.getContext('2d');
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
            isDrawing = true;
        }

        function draw(e) {
            if (!isDrawing) return;
            const canvas = e.target;
            const ctx = canvas.getContext('2d');
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function clearCanvas() {
            const canvas = document.getElementById('signatureCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveCanvas() {
            const canvas = document.getElementById('signatureCanvas');
            const dataURL = canvas.toDataURL('image/png');
            document.getElementById('signatureInput').value = dataURL;
        }
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">



        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Create Tanda Tangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Flash Message -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php elseif (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <!-- Form -->
                    <div class="card">
                        <div class="card-body">
                            <form action="/notulen/tandatangan/store" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="notulen_id" value="<?= $notulen_id ?>">

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="departemen">Departemen</label>
                                    <input type="text" name="departemen" id="departemen" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="signature">Tanda Tangan</label>
                                    <canvas id="signatureCanvas" width="400" height="200" style="border:1px solid #000;"
                                        onmousedown="startDrawing(event)"
                                        onmousemove="draw(event)"
                                        onmouseup="stopDrawing()"
                                        onmouseleave="stopDrawing()">
                                    </canvas>
                                    <button type="button" class="btn btn-warning mt-2" onclick="clearCanvas()">Clear</button>
                                    <input type="hidden" name="signature" id="signatureInput" required>
                                </div>

                                <button type="submit" class="btn btn-primary" onclick="saveCanvas()">Submit</button>
                                <a href="/notulen" class="btn btn-secondary ml-2">
                                    Kembali
                                </a>
                            </form>
                        </div>
                    </div>

<!-- Display Tanda Tangan -->
<h3>Daftar Tanda Tangan</h3>
<div class="row">
    <?php if (!empty($tandatanganList)): ?>
        <?php foreach ($tandatanganList as $tt): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p><strong>Nama:</strong> <?= esc($tt['nama']) ?></p>
                        <p><strong>Departemen:</strong> <?= esc($tt['departemen']) ?></p>
                        <img src="/tandatangan/<?= esc($tt['tandatangan']) ?>" alt="Tanda Tangan" class="img-fluid" style="border:1px solid #000;">

                        <!-- Tombol Edit -->
                        <a href="/notulen/tandatangan/edit/<?= esc($tt['id']) ?>" class="btn btn-warning btn-sm mt-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="/notulen/tandatangan/delete/<?= esc($tt['id']) ?>" method="get" class="mt-2">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus tanda tangan ini?')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Belum ada tanda tangan.</p>
    <?php endif; ?>
</div>


                </div>
            </section>
        </div>

    </div>

    <!-- Tambahkan Script AdminLTE -->
    <script src="/assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/adminlte/dist/js/adminlte.min.js"></script>
</body>

</html>