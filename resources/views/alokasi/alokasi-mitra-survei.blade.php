@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                @if (session()->has('sample'))
                    @include('sweetalert::alert')
                @endif
                <input type="hidden" id="session-id-bs"
                    value="{{ isset(session()->get('sample')['id_bs_sample']) ? json_encode(session()->get('sample')['id_bs_sample']) : '' }}">
                <h4 class="mt-4 mb-2">Rekomendasi Alokasi Mitra Kegiatan
                    <strong>{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                </h4>
                @if (session()->has('sample'))
                    <a href="{{ url('/resetUploadSample') }}" class="btn btn-outline-success"><i
                            class="fa-solid fa-rotate-right pr-2"></i>Ulangi Upload Sample</a>
                @endif
                <div class="row justify-content-center mt-3 overview-survey-location">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header" id="map-alokasi-wilkerstat">
                                <i class="fa-solid fa-map"></i>
                                Overview Peta Sampel BS
                                <strong>{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                                BPS Kota Gunungsitoli
                            </div>
                            <div class="card-body" id="overview-survey"><canvas id="overview-survey" width="100%"
                                    height="40"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-location-crosshairs"></i>
                        Alokasi Wilayah Kerja
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/alokasi_mitra_survei') }}" method="post" id="form-data-mitra">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Kota/Kabupaten</label>
                                        <select class="form-select" id="selectRegency"></select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <select class="form-select" id="selectDistrict" name="selectDistrict"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Desa</label>
                                        <select class="form-select" id="selectVillage" name="selectVillage"></select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Pilih Blok Sensus</label>
                                        <select class="form-select" id="selectBS" name="selectBS"></select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="lihat-daftar-mitra" data-bs-dismiss="modal"
                                name="submit-match-bs" hidden><i class="fa-solid fa-list pr-1"></i>Lihat
                                Daftar Mitra</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="spinner-grow loader text-secondary loading-alokasi-survei" role="status" hidden>
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="row justify-content-center mt-3 mitra-sesuai-wilkerstat" hidden>
            <div class="col-xl-11">
                <div class="card mb-4">
                    <div class="card-header" id="map-alokasi-wilkerstat">
                        <i class="fa-solid fa-map"></i>
                        Peta Persebaran Mitra BPS Kota Gunungsitoli (1278)
                    </div>
                    <div class="card-body" id="map-alokasi-mitra"><canvas id="map-alokasi-mitra" width="100%"
                            height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center tabel-daftar-mitra" hidden>
            <div class="col-xl-11">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Mitra yang Direkomendasikan
                    </div>
                    <div class="card-body">
                        <table id="data-table-mitra" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sobat ID</th>
                                    <th>Nama Mitra</th>
                                    <th>Alamat Mitra</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Jarak Mitra ke BS</th>
                                    <th>Lokasi Mitra</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
