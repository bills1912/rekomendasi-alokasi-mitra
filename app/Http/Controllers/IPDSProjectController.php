<?php

namespace App\Http\Controllers;

use App\Models\AlokasiMitraSurvei;
use Illuminate\Support\Facades\Session;
use App\Models\DaftarMitra;
use App\Models\DaftarSurveiModel;
use App\Models\DistrictModel;
use App\Models\GusitSLSModel;
use App\Models\GusitBSModel;
use App\Models\ProvinceModel;
use App\Models\RegencyModel;
use App\Models\VillageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
// use Input;

class IPDSProjectController extends Controller
{
    // Core

    public function index()
    {
        $data_mitra_total = DaftarMitra::all();
        if (Auth::check() && Auth::user()->is_sm) {
            return view('map_table', [
                'data_mitra_total' => $data_mitra_total
            ]);
        } else {
            return view('mitra_view.container.mitra_index');
        }
    }

    // Admin Side

    public function alokasiMitra(Request $request)
    {
        return view('alokasi.alokasi-mitra-survei', [
            'test' => $request->file('file'),
        ]);
    }

    public function daftarMitarTeralokasi()
    {
        // dd(Session::get('sample')['nama_kegiatan_survei']);
        $data_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get();
        return view('alokasi.daftar-mitra-survei-teralokasi', [
            'list_mitra_terpilih' => $data_mitra_terpilih,
        ]);
    }

    public function uploadSampleBlokSensus()
    {
        return view('alokasi.upload-sample-bs');
    }

    public function ulangiUploadSample()
    {
        Session::forget('sample');
        Alert::success('Berhasil', 'Reset upload sampel survei berhasil');
        return redirect('/upload_sample_bs');
    }

    public function ambilSampelWilkerstat(Request $request)
    {
        $data = Excel::toArray([], $request->file('unggahSample'));
        foreach (array_slice($data[0], 1) as $id) {
            $insert_data[] = $id[0];
        };
        $data_mitra = Excel::toArray([], $request->file('unggahMitraTerpilih'));
        foreach (array_slice($data_mitra[0], 1) as $id) {
            $insert_data_mitra[] = $id[1];
        };

        DaftarSurveiModel::create([
            'daftar_kegiatan_survei' => strtoupper($request->input('initializeSurvei'))
        ]);

        Session::put('sample', [
            'id_bs_sample' => $insert_data,
            'mitra_sample' => $insert_data_mitra,
            'nama_kegiatan_survei' => strtoupper($request->input('initializeSurvei')),
            // 'sample_terunggah' => $request->file('unggahSample'),
            // 'mitraTerpilih_terunggah' => $request->file('unggahMitraTerpilih'),
        ]);
        Alert::success('Selamat', 'Sample blok sensus dan data mitra terpilih sudah berhasil diunggah');
        return redirect()->route('alokasi_mitra.id_sls');
    }

    public function pilihKako()
    {
        $ids_sample_kako = Session::get('sample')['id_bs_sample'];
        $id_kako_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 4);
        }, $ids_sample_kako));
        $data = RegencyModel::where('id', $id_kako_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihKec($id_kec)
    {
        $ids_sample_kec = Session::get('sample')['id_bs_sample'];
        $id_kec_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 7);
        }, $ids_sample_kec));
        $data = DistrictModel::where('regency_id', $id_kec)->whereIn('id', $id_kec_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihDesa($id_desa)
    {
        $ids_sample_desa = Session::get('sample')['id_bs_sample'];
        $id_desa_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 10);
        }, $ids_sample_desa));
        $data = VillageModel::where('district_id', $id_desa)->whereIn('id', $id_desa_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihBS($id_bs)
    {
        $ids_sample_bs = Session::get('sample')['id_bs_sample'];
        $data = GusitBSModel::whereIn(trim('idbs'), $ids_sample_bs)->where(trim('iddesa'), '=', $id_bs)->where('nmsls', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function inisialisasiKegiatanSurvei()
    {
        $data = DaftarSurveiModel::select('daftar_kegiatan_survei')->distinct()->where('daftar_kegiatan_survei', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function ambilIDBSMitra(Request $request)
    {
        $id_kec_mitra = Session::get('sample')['mitra_sample'];
        $id_kecamatan = $request->input('selectDistrict');
        $id_mitra_dialokasikan = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get('id');
        $arrIDMitraDialokasikan = [];
        foreach ($id_mitra_dialokasikan as $data => $value) {
            $arrIDMitraDialokasikan[] = $value->id;
        }
        $selected_mitra = DaftarMitra::whereIn("id_kec", $id_kec_mitra)
            ->where(function ($query) use ($arrIDMitraDialokasikan) {
                $query->whereNotIn('id', $arrIDMitraDialokasikan);
            })
            ->where("id_kec", '=', $id_kecamatan)
            ->get();
        Session::put('bs_terpilih', $request->input('selectBS'));
        return $selected_mitra;
    }

    public function pengalokasianMitraSurvei($id)
    {
        $mitraDialokasikan = DaftarMitra::where('id', $id)->get();
        foreach ($mitraDialokasikan as $data => $value) {
            AlokasiMitraSurvei::create([
                'id' => $value->id,
                // 'id_desa' => $value->id_desa_mitra,
                'nama_mitra' => $value->nama,
                'alamat_mitra' => $value->alamat_detail,
                // 'jarak_mitra' => acos(sin(DaftarMitra::select('latitude')->where('id', $id)->get())),
                // 'status_mitra' => DaftarMitra::select('status_mitra')->where('id', $id)->get(),
                'kegiatan_survei' => strtoupper(Session::get('sample')['nama_kegiatan_survei']),
                'idbs_teralokasi' => Session::get('bs_terpilih'),
            ]);
        }

        Alert::success('Berhasil', 'Mitra berhasil dialokasikan pada blok sensus');
        return redirect('/alokasi_mitra_survei');
    }

    public function editDataMitraSurveiTeralokasi(Request $request, $id)
    {
        AlokasiMitraSurvei::where('super_id', $id)
            ->update([
                'nama_mitra' => $request->input('namaMitra'),
                'alamat_mitra' => $request->input('alamatMitra'),
                // 'jarak_mitra' => acos(sin(DaftarMitra::select('latitude')->where('id', $id)->get())),
                // 'status_mitra' => DaftarMitra::select('status_mitra')->where('id', $id)->get(),
                'kegiatan_survei' => $request->input('kegiatanSurvei'),
                'idbs_teralokasi' => $request->input('idbsTeralokasi'),
            ]);

        Alert::success('Berhasil', 'Data mitra berhasil diperbaharui');
        return redirect('/list_mitra_survei_teralokasi');
    }

    public function hapusDataMitraSurveiTeralokasi($id)
    {
        AlokasiMitraSurvei::where('super_id', $id)->delete();
        Alert::success('Berhasil', 'Data Berhasil Dihapus');
        return redirect('/list_mitra_survei_teralokasi');
    }

    // Mitra Side

    public function daftarKegiatanMitra() {
        return view('mitra_view.container.mitra_daftar_kegiatan');
    }
}
