<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminTransaksiController extends Controller
{
    public function index()
    {
        $transaksi = DB::table('peminjaman')
            ->join('siswa', 'peminjaman.id_siswa', '=', 'siswa.id_siswa')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('buku', 'detail_peminjaman.id_buku', '=', 'buku.id_buku')
            ->select(
                'peminjaman.*',
                'siswa.nama_siswa',
                'siswa.nis',
                'siswa.kelas',
                'siswa.jurusan',
                'buku.judul_buku',
                'detail_peminjaman.jumlah'
            )
            ->orderBy('peminjaman.created_at', 'desc')
            ->get();

        return view('admin.transaksi.index', compact('transaksi'));
    }
}
