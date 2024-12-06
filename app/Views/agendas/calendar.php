<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Agenda</title>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="path/to/adminlte.min.css">

    <!-- FullCalendar JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AdminLTE JavaScript -->
    <script src="path/to/adminlte.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?= $title; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Agenda Management</a></li>
                            <li class="breadcrumb-item active"><?= $title; ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title; ?></h3>
                    <?php if (in_groups('admin')|| in_groups('pimpinan')): ?>
                    <div class="card-tools">
                        <select id="filterAgenda" class="form-control">
                            <option value="all">Lihat Semua</option>
                            <option value="mine">Lihat Agenda Saya</option>
                        </select>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Card Body with Calendar -->
                <div class="card-body">
                    <div id="calendar"></div>
                </div>

                <div class="card-footer">
                    <!-- Optional Footer Content -->
                </div>
            </div>
        </section>
    </div>

    <!-- Footer (optional) -->
    <footer class="main-footer">
        <!-- Footer content here -->
    </footer>
</div>

<script>
    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');

        function loadCalendar(filter) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id', // Locale Indonesia
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {
                    url: '/events', // Endpoint untuk mengambil data agenda
                    method: 'GET',
                    extraParams: {
                        filter: filter // Kirim filter (all atau mine) sebagai parameter
                    },
                    failure: function() {
                        alert('Gagal mengambil data!');
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.extendedProps.link) {
                        window.open(info.event.extendedProps.link, '_blank');
                    }
                },
                eventTimeFormat: { 
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                eventContent: function(info) {
                    let html = '<div>' +
                               '<b>' + info.event.title + '</b><br>' +
                               '<span>' + info.timeText + '</span><br>' +
                               '<a href="' + info.event.extendedProps.link + '" class="btn btn-info btn-sm" title="Detail" target="_blank">' +
                               ' Detail</a>' +
                               '</div>';
                    return { html: html };
                }
            });
            calendar.render();
        }

        // Load calendar with default filter
        loadCalendar('all');

        // Event listener for dropdown change
        $('#filterAgenda').on('change', function() {
            var filter = $(this).val();
            $('#calendar').html(''); // Reset kalender
            loadCalendar(filter); // Muat ulang kalender dengan filter baru
        });
    });
</script>
</body>
</html>
