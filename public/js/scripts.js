/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

$('#mitra-survei-teralokasi').DataTable({
    // dom: 'lBfrtip<"action">',
    responsive: true,
    columnDefs: [
        { width: 230, targets: [0] },
        { width: 330, targets: [2] },
        { width: 180, targets: [1, 3] },
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [3, 4] },
    ]
});

$('#mitra-survei-teralokasi').on('click', '.hapusDataMitraSurveiTeralokasi', function (e) {
    e.preventDefault();
    // alert('halo');
    let href = $(this).attr('href');
    Swal.fire({
        title: "Hapus Data!",
        text: "Apakah kamu yakin ingin menghapus data?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});
