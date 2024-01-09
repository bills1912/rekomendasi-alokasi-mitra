<?php

use App\Http\Controllers\IPDSProjectController;
use App\Http\Controllers\IPDSProjectSLSController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('selectRegency', [IPDSProjectController::class, 'pilihKako']);
Route::get('selectDistrict/{id_kec}', [IPDSProjectController::class, 'pilihKec']);
Route::get('selectVillage/{id_desa}', [IPDSProjectController::class, 'pilihDesa']);
Route::get('selectBS/{id_bs}', [IPDSProjectController::class, 'pilihBS']);
Route::get('selectSurvei', [IPDSProjectController::class, 'pilihKegiatanSurvei']);
Route::get('/resetUploadSample', [IPDSProjectController::class, 'ulangiUploadSample']);

Route::get('/list_mitra_survei_teralokasi/{id}', [IPDSProjectController::class, 'hapusDataMitraSurveiTeralokasi']);
Route::post('/list_mitra_survei_teralokasi/{id}', [IPDSProjectController::class, 'editDataMitraSurveiTeralokasi']);

Route::get('initializeSurvei', [IPDSProjectController::class, 'inisialisasiKegiatanSurvei']);

Route::get('selectProvSensus', [IPDSProjectSLSController::class, 'pilihProvinsiSensus']);
Route::get('selectRegencySensus/{id_kako}', [IPDSProjectSLSController::class, 'pilihKakoSensus']);
Route::get('selectDistrictSensus/{id_kec}', [IPDSProjectSLSController::class, 'pilihKecSensus']);
Route::get('selectVillageSensus/{id_desa}', [IPDSProjectSLSController::class, 'pilihDesaSensus']);
Route::get('selectSLS/{id_sls}', [IPDSProjectSLSController::class, 'pilihSLS']);
Route::post('/alokasi_mitra_survei', [IPDSProjectController::class, 'ambilIDBSMitra']);
Route::post('/alokasi_mitra_sensus', [IPDSProjectSLSController::class, 'ambilIDSLSMitra']);
Route::post('/upload-sample-bs', [IPDSProjectController::class, 'ambilSampelWilkerstat']);
Route::post('/upload-mitra-terpilih', [IPDSProjectSLSController::class, 'ambilMitraSensusTerpilih']);
Route::get('/alokasi_mitra_survei/{id}', [IPDSProjectController::class, 'pengalokasianMitraSurvei']);

Route::middleware('auth')->group(function () {
    // Survei
    Route::get('/', [IPDSProjectController::class, 'index'])->name("map_table");
    Route::get('/alokasi_mitra_survei', [IPDSProjectController::class, 'alokasiMitra'])->name("alokasi_mitra.id_sls");
    Route::get('/upload_sample_bs', [IPDSProjectController::class, 'uploadSampleBlokSensus'])->name("sample_wilkerstat");
    Route::get('/list_mitra_survei_teralokasi', [IPDSProjectController::class, 'daftarMitarTeralokasi']);

    // Sensus
    Route::get('/alokasi_mitra_sensus', [IPDSProjectSLSController::class, 'alokasiMitraSensus'])->name("mitra_sensus");
    Route::get('/upload_mitra_terpilih', [IPDSProjectSLSController::class, 'uploadMitraSensusTerpilih'])->name("mitra_sensus_terpilih");

    // Mitra Side
    Route::get('/mitra_daftar_kegiatan', [IPDSProjectController::class, 'daftarKegiatanMitra']);
});

require __DIR__ . '/auth.php';
