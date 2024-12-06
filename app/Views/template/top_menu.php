<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <?php
        $notifications = session()->get('notifications') ?? [];
        $notificationCount = count($notifications);
        ?>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php if ($notificationCount > 0): ?>
                    <span class="badge badge-danger navbar-badge"><?= $notificationCount ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= $notificationCount ?> Agendas Tomorrow</span>
                <div class="dropdown-divider"></div>
                <?php if ($notificationCount > 0): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-calendar-alt mr-2"></i> <?= $notification['judul'] ?>
                            <span class="float-right text-muted text-sm">
                                <?= date('d-m-Y H:i', strtotime($notification['tanggal_kegiatan'] . ' ' . $notification['jam_kegiatan'])) ?>
                            </span>
                        </a>
                        <!-- Menambahkan tombol "Detail" dengan ID agenda -->
                        <div class="dropdown-divider"></div>
                        <a href="/agenda/detail/<?= esc($notification['id']) ?>" class="dropdown-item">
                            <button class="btn btn-info btn-sm float-right" title="Detail">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </a>
                        <div class="dropdown-divider"></div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="#" class="dropdown-item text-center">No Agenda</a>
                <?php endif; ?>
                <a href="<?= base_url('calendar'); ?>" class="nav-link" class="dropdown-item dropdown-footer">See All My Agenda</a>

            </div>
        </li>


        <!-- Logout item as dropdown -->
        <li class="nav-item dropdown">
            <?php if (logged_in()): ?>
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i> <?= user()->username; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user-cog"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/logout" onclick="return confirm('Yakin ingin log out?');">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            <?php else : ?>
                <a class="nav-link" href="/login" role="button">
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </a>
            <?php endif; ?>
        </li>



    </ul>
</nav>
<!-- /.navbar -->