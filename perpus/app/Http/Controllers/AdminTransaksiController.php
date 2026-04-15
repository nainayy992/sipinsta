<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = Peminjaman::with(['siswa', 'details.buku'])
            ->select('peminjaman.*', 'siswa.nama_siswa', 'siswa.nis', 'siswa.kelas', 'siswa.jurusan')
            ->addSelect('detail_peminjaman.jumlah', 'buku.judul_buku')
            ->leftJoin('siswa', 'peminjaman.id_siswa', '=', 'siswa.id_siswa')
            ->leftJoin('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->leftJoin('buku', 'detail_peminjaman.id_buku', '=', 'buku.id_buku')
            ->orderBy('peminjaman.created_at', 'desc')
            ->paginate(10);

        return view('admin.transaksi.index', compact('transaksi'));
    }
}
