<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard siswa
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Rekomendasi buku
        $recommendedBooks = Buku::whereIn('foto', [
            'bukan 350 tahun.jpg',
            'home for allie.jpg',
            'van der wijck.jpg',
            'hujan.jpg'
        ])->get();

        $siswa = Siswa::where('nis', $user->nis)->first();

        $activeLoansCount = 0;

        if ($siswa) {
            $activeLoansCount = DB::table('peminjaman')
                ->where('id_siswa', $siswa->id_siswa)
                ->where('status', 'Dipinjam')
                ->count();
        }

        return view('siswa.dashboard', compact('recommendedBooks', 'activeLoansCount'));
    }

    /**
     * Cari buku
     */
    public function cari(Request $request)
    {
        $query = Buku::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->has('kategori') && is_array($request->kategori)) {
            $query->whereIn('id_kategori', $request->kategori);
        }

        $buku = $query->with('kategori')->get();

        $kategori = Kategori::whereIn('nama_kategori', ['Fiksi', 'Sejarah'])->get();

        return view('siswa.cari_buku', compact('buku', 'kategori'));
    }

    /**
     * Detail buku (JSON)
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return response()->json($buku);
    }

    /**
     * Pinjam buku
     */
    public function pinjam(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User belum login.'], 401);
        }

        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku habis.'], 400);
        }

        $siswa = Siswa::where('nis', $user->nis)->first();

        if (!$siswa) {
            Log::error("Siswa tidak ditemukan untuk user ID: {$user->id}, NIS: {$user->nis}");
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan.'], 404);
        }

        // Cek pinjaman aktif
        $existingLoan = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->where('peminjaman.id_siswa', $siswa->id_siswa)
            ->where('detail_peminjaman.id_buku', $id)
            ->where('peminjaman.status', 'Dipinjam')
            ->exists();

        if ($existingLoan) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini masih dalam pinjaman Anda.'
            ], 400);
        }

        DB::transaction(function () use ($buku, $siswa) {

            $peminjamanId = DB::table('peminjaman')->insertGetId([
                'id_siswa' => $siswa->id_siswa,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(7),
                'status' => 'Dipinjam',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('detail_peminjaman')->insert([
                'id_peminjaman' => $peminjamanId,
                'id_buku' => $buku->id_buku,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $buku->decrement('stok');
        });

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam.'
        ]);
    }

    /**
     * Riwayat peminjaman
     */
    public function peminjaman()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('nis', $user->nis)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $peminjaman = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('buku', 'detail_peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_siswa', $siswa->id_siswa)
            ->select(
                'peminjaman.*',
                'buku.judul_buku',
                'buku.id_buku',
                'detail_peminjaman.jumlah'
            )
            ->orderBy('peminjaman.created_at', 'desc')
            ->get();

        return view('siswa.peminjaman', compact('peminjaman'));
    }

    /**
     * Kembalikan buku
     */
    public function kembalikan(Request $request, $id_peminjaman)
    {
        $peminjaman = DB::table('peminjaman')
            ->where('id_peminjaman', $id_peminjaman)
            ->first();

        if (!$peminjaman || $peminjaman->status === 'Dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid atau sudah dikembalikan.'
            ], 400);
        }

        $detail = DB::table('detail_peminjaman')
            ->where('id_peminjaman', $id_peminjaman)
            ->first();

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detail tidak ditemukan.'
            ], 404);
        }

        DB::transaction(function () use ($id_peminjaman, $detail) {

            DB::table('peminjaman')
                ->where('id_peminjaman', $id_peminjaman)
                ->update([
                    'status' => 'Dikembalikan',
                    'updated_at' => now()
                ]);

            Buku::where('id_buku', $detail->id_buku)
                ->increment('stok');
        });

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan.'
        ]);
    }
}
