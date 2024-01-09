// let mapAlokasiMitra = L.map('map-alokasi-mitra');
// let layerGroup = L.featureGroup();

let bsCentroid = [];
let coords_mitra_wilkerstat = [];
let jarakMitraBS = [];
let mitraWilkerstatMarkerGroup = L.markerClusterGroup();
let bsSurveiGroup = L.featureGroup();

// Overview Survey Location Map
let overviewMap = L.map('overview-survey');
let groupOverviewBS = L.featureGroup();
let osmOverviewSurvey = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})

let legendOverview = L.control({
    position: "bottomright"
});

legendOverview.onAdd = function () {
    let div = L.DomUtil.create("div", "legend");
    div.innerHTML += "<h4><strong>Legend</strong></h4>";
    div.innerHTML += '<span>Keterangan:</span><br>';
    div.innerHTML += '<i style="background: #472c4c"></i><span>Sample BS Terpilih</span><br>';
    div.innerHTML += '<i style="background: #7AD151FF"></i><span>BS Lainnya</span><br>';

    return div;
};

legendOverview.addTo(overviewMap);

let legendAlokasi = L.control({
    position: "bottomright"
});

legendAlokasi.onAdd = function () {
    let div = L.DomUtil.create("div", "legend");
    div.innerHTML += "<h4><strong>Legend</strong></h4>";
    div.innerHTML += '<span>Keterangan:</span><br>';
    div.innerHTML += '<i style="background: #472c4c"></i><span>Sample BS Terpilih</span><br>';
    div.innerHTML += '<i style="background: #7AD151FF"></i><span>BS Tetangga</span><br>';

    return div;
};

legendAlokasi.addTo(mapAlokasiMitra);

osmOverviewSurvey.addTo(overviewMap);
$(document).ready(function () {
    // Overview Part
    overviewMap.setView([1.2840493727755393, 97.60681662154084], 13);
    setTimeout(function () {
        overviewMap.invalidateSize();
    }, 1);
    fetch("js/1278_finalbs_2023_sem2.geojson")
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            try {
                let selectedBS = JSON.parse($('#session-id-bs').val());
                for (let i = 0; i <= data['features'].length; i++) {
                    let overview_bs_geom = L.geoJSON(data['features'][i], {
                        style: overviewStyle,
                        onEachFeature: bsOnEachFeature
                    });
                    groupOverviewBS.addTo(overviewMap);
                    groupOverviewBS.addLayer(overview_bs_geom);
                    function bsOnEachFeature(feature, layer) {
                        layer.on({
                            mouseover: function () {
                                this.setStyle({
                                    'weight': 5,
                                    'color': '#666'
                                });
                                this.bringToFront();
                            },
                            mouseout: function () {
                                this.setStyle({
                                    'color': 'white',
                                    'weight': 2
                                });
                                // mitraInfo.update();
                            },
                            click: function () {
                                layer.bindPopup(
                                    "<h5 class='text-dark'>" + feature.properties.nmdesa + " (<strong>" + feature.properties.idbs + ")</strong>" + "</h5>" +
                                    "<p style='font-size: 16px;'>Luas BS: <strong>" + feature.properties.luas + " m" + '<sup>2</sup></strong>' +
                                    "<br>Muatan KK: <strong>" + feature.properties.kk + "</strong>" +
                                    "<br>SLS: <strong>" + feature.properties.nmsls + "</strong></p>", {
                                    maxHeight: 300,
                                    minWidth: 200,
                                    maxWidth: 600
                                }).openPopup();
                            }
                        });
                    };
                    function overviewStyle(feature) {
                        if (!selectedBS.includes(feature.properties.idbs)) {
                            return {
                                fillColor: "#228B22",
                                fillOpacity: 0.65,
                                color: '#ffffff',
                                opacity: 1,
                                weight: 1,
                            }
                        } else {
                            return {
                                fillColor: "#472c4c",
                                fillOpacity: 0.65,
                                color: "#fff",
                                weight: 1,
                            }
                        };
                    };
                };
            }
            catch (e) {
                console.log(e);
            }
        });

    // Next Part
    $("#selectBS").change(function () {
        $("#lihat-daftar-mitra").removeAttr('hidden');
    });
    $("#form-data-mitra").submit(function (e) {
        e.preventDefault();
        sessionStorage.setItem('bsTerpilih', $("#selectBS").val());
        $('.loading-alokasi-survei').removeAttr('hidden');
        $(".mitra-sesuai-wilkerstat").attr("hidden", true);
        $(".tabel-daftar-mitra").attr('hidden', true);
        $spinner = document.getElementsByClassName("loading-alokasi-survei")[0];
        $spinner.style.display = "block";
        setTimeout(() => {
            $spinner.style.display = "none";
            $(".mitra-sesuai-wilkerstat").removeAttr("hidden");
            setTimeout(function () {
                mapAlokasiMitra.invalidateSize();
            }, 1);
            $(".tabel-daftar-mitra").removeAttr('hidden');
        }, 2000);
        bsCentroid = [];
        coords_mitra_wilkerstat = [];
        bsSurveiGroup.clearLayers();
        layerGroup.clearLayers();
        let idDesa = $("#selectVillage").val();
        let idBS = $("#selectBS").val();
        fetch("js/gusit_bs.geojson")
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                try {
                    console.log(data);
                    let selectedBS = JSON.parse($('#session-id-bs').val());
                    for (let i = 0; i <= data['features'].length; i++) {
                        if (data['features'][i].properties.idbs == idBS) {
                            let bs_geom = L.geoJSON(data['features'][i], {
                                style: bsStyle,
                                onEachFeature: bsOnEachFeature
                            });
                            layerGroup.addTo(mapAlokasiMitra);
                            layerGroup.addLayer(bs_geom);
                            bsCentroid.push([layerGroup.getBounds().getCenter()['lat'], layerGroup.getBounds().getCenter()['lng']]);
                            layerGroup.addLayer(L.marker(bsCentroid[0], { icon: L.AwesomeMarkers.icon({ icon: 'map', prefix: 'fa', markerColor: 'red' }) }));
                            function bsStyle() {
                                return {
                                    fillColor: "#ffcccb",
                                    fillOpacity: 0,
                                    color: "#fff",
                                    opacity: 1,
                                    weight: 1,
                                }
                            };
                            function bsOnEachFeature(feature, layer) {
                                layer.on({
                                    click: function () {
                                        layer.bindPopup(
                                            "<h5 class='text-dark'>" + feature.properties.nmdesa + " (<strong>" + feature.properties.idbs + ")</strong>" + "</h5>" +
                                            "<p style='font-size: 16px;'>Luas BS: <strong>" + feature.properties.luas + " m" + '<sup>2</sup></strong>' +
                                            "<br>Muatan KK: <strong>" + feature.properties.kk + "</strong></p>", {
                                            maxHeight: 300,
                                            minWidth: 200,
                                            maxWidth: 600
                                        }).openPopup();
                                    }
                                });
                            };
                        };
                        if (data['features'][i].properties.iddesa == idDesa) {
                            let group_bs_geom = L.geoJSON(data['features'][i], {
                                style: bsgroupStyle,
                                onEachFeature: bsOnEachFeature
                            });
                            bsSurveiGroup.addTo(mapAlokasiMitra);
                            bsSurveiGroup.addLayer(group_bs_geom);
                            function bsgroupStyle(feature) {
                                if (!selectedBS.includes(feature.properties.idbs)) {
                                    return {
                                        fillColor: "#7AD151FF",
                                        fillOpacity: 0.6,
                                        color: '#666',
                                        opacity: 1,
                                        weight: 3,
                                    }
                                } else {
                                    return {
                                        fillColor: "#472c4c",
                                        fillOpacity: 0.6,
                                        color: "#ffffff",
                                        weight: 3,
                                    }
                                };
                            };
                            function bsOnEachFeature(feature, layer) {
                                layer.on({
                                    click: function () {
                                        layer.bindPopup(
                                            "<h5 class='text-dark'>" + feature.properties.nmdesa + " (<strong>" + feature.properties.idbs + ")</strong>" + "</h5>" +
                                            "<p style='font-size: 16px;'>Luas BS: <strong>" + feature.properties.luas + " m" + '<sup>2</sup></strong>' +
                                            "<br>Muatan KK: <strong>" + feature.properties.kk + "</strong></p>", {
                                            maxHeight: 300,
                                            minWidth: 200,
                                            maxWidth: 600
                                        }).openPopup();
                                    }
                                });
                            };
                        };
                    };
                }
                catch (e) {
                    console.log(e);
                }
            });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/alokasi_mitra_survei",
            data: $("#form-data-mitra").serialize(),
            type: "POST",
            dataType: "json",
            success: function (result) {
                let data_mitra = result
                console.log(data_mitra);
                $("#data-table-mitra").DataTable().destroy();
                try {
                    $("#data-table-mitra").DataTable({
                        data: data_mitra,
                        responsive: true,
                        // rowReorder: true,
                        order: [[5, "asc"]],
                        columnDefs: [
                            // { targets: [3, 4], visible: false },
                            { className: "dt-head-center", targets: '_all' },
                            { className: "dt-body-center", targets: [3, 4, 5, 6] },
                            { orderable: false, targets: '_all' }
                        ],
                        columns: [
                            { data: "sobat_id" },
                            { data: "nama" },
                            { data: "alamat_detail" },
                            { data: "latitude" },
                            { data: "longitude" },
                            {
                                data: null,
                                render: function (data, type, row) {
                                    return Math.acos(Math.sin(row.latitude) * Math.sin(bsCentroid[0][0]) + Math.cos(row.latitude) * Math.cos(bsCentroid[0][0]) * Math.cos(bsCentroid[0][1] - row.longitude)) * 6371;
                                }
                            },
                            {
                                data: null,
                                render: function (data, type, row) {
                                    return '<button class="btn btn-outline-primary btn-sm cari-mitra-wilkerstat mr-1"><i class="fa-solid fa-magnifying-glass"></i></button>' +
                                        '<a href="/alokasi_mitra_survei/' + row.id + '" class="btn btn-outline-warning btn-sm btn-alokasi-mitra-survei"\
                                        data-bs-toggle="modal" data-bs-target="#alokasiMitraSurveiModal">\
                                        <i class="fa-solid fa-thumbtack px-0"></i></a>'
                                }
                            }
                        ]
                    });
                    $('#data-table-mitra').on('click', '.btn-alokasi-mitra-survei', function (e) {
                        e.preventDefault();
                        let href = $(this).attr('href');
                        Swal.fire({
                            title: "Alokasikan Mitra",
                            text: "Apakah kamu yakin ingin mengalokasikan mitra pada blok sensus ini?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ya, alokasikan",
                            cancelButtonText: "Tidak",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = href;
                            }
                        });
                    });
                } catch (e) {
                    console.log(e);
                }
                mapAlokasiMitra.setView(bsCentroid[0], 16);
                mitraWilkerstatMarkerGroup.clearLayers();
                try {
                    for (let i = 0; i <= data_mitra.length; i++) {
                        coords_mitra_wilkerstat.push([data_mitra[i].latitude, data_mitra[i]['longitude']]);
                        mitraWilkerstatMarkerGroup.addLayer(L.marker(coords_mitra_wilkerstat[i], {
                            icon: L.icon({
                                iconUrl: window.location.protocol + "//" + window.location.host + '/img/man.png',
                                iconSize: [30, 30]
                            })
                        }).bindPopup('lorem ipsum'));
                        mapAlokasiMitra.addLayer(mitraWilkerstatMarkerGroup)
                        $("#data-table-mitra").on("click", ".cari-mitra-wilkerstat", function () {
                            var mitraIdx = $(this).closest('tr').index();
                            mapAlokasiMitra.flyTo([parseFloat(document.getElementById("data-table-mitra").rows[mitraIdx + 1].childNodes[3].innerHTML.replace("'", "")),
                            parseFloat(document.getElementById("data-table-mitra").rows[mitraIdx + 1].childNodes[4].innerHTML.replace("'", ""))], 18)
                        });
                    };
                } catch (e) {
                    console.log(e);
                }
            }
        });
        return false;
    });
    $(".btn-select-event").click(function () {
        $.ajax({
            url: '/alokasi-mitra',
            data: $('input[name="select-event"]:checked').val(),
            type: "POST"
        });
        alert($('input[name="select-event"]:checked').val());
    });
});