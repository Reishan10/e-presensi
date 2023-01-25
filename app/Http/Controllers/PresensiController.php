<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $tgl = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = Presensi::where(['tgl_presensi' => $tgl, 'nik' => $nik])->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date('Y-m-d');
        $jam_masuk = date('H:i:s');
        $image = $request->image;
        $lokasi = $request->lokasi;

        $folderPath = 'public/uploads/absensi/';
        $formatName = $nik . ' - ' . $tgl_presensi;

        $image_parts = explode(';base64', $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;



        $cek = Presensi::where(['tgl_presensi' => $tgl_presensi, 'nik' => $nik])->count();
        if ($cek > 0) {
            $simpan = Presensi::where(['tgl_presensi' => $tgl_presensi, 'nik' => $nik])->update([
                'jam_keluar' => $jam_masuk,
                'foto_keluar' => $fileName,
                'lokasi_keluar' => $lokasi,
            ]);
            if ($simpan) {
                echo 1;
                Storage::put($file, $image_base64);
            } else {
                echo 0;
            }
        } else {
            $simpan = Presensi::create([
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_masuk' => $jam_masuk,
                'foto_masuk' => $fileName,
                'lokasi_masuk' => $lokasi,
            ]);

            if ($simpan) {
                echo 1;
                Storage::put($file, $image_base64);
            } else {
                echo 0;
            }
        }
    }
}
