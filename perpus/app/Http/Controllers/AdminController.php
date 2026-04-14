<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        $siswaCount = User::where('role', 'siswa')->count();
        $bukuCount = \App\Models\Buku::count();
        $pinjamCount = DB::table('peminjaman')
            ->where('status', 'Dipinjam')
            ->count();

        // Latest Siswa
        $latestSiswa = User::where('role', 'siswa')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'siswa',
                    'title' => 'Anggota Baru',
                    'description' => 'Siswa ' . $item->name . ' telah terdaftar.',
                    'time' => $item->created_at,
                    'icon' => 'bi-person-plus',
                    'color' => 'primary'
                ];
            });

        // Latest Buku
        $latestBuku = \App\Models\Buku::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'buku',
                    'title' => 'Buku Baru',
                    'description' => 'Buku "' . $item->judul_buku . '" telah ditambahkan.',
                    'time' => $item->created_at,
                    'icon' => 'bi-book',
                    'color' => 'maroon'
                ];
            });

        // Latest Peminjaman
        $latestPinjam = DB::table('peminjaman')
            ->join('siswa', 'peminjaman.id_siswa', '=', 'siswa.id_siswa')
            ->whereDate('peminjaman.created_at', today())
            ->orderBy('peminjaman.created_at', 'desc')
            ->take(5)
            ->select('peminjaman.*', 'siswa.nama_siswa')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'transaksi',
                    'title' => 'Transaksi Baru',
                    'description' => $item->nama_siswa . ' telah meminjam buku.',
                    'time' => Carbon::parse($item->created_at),
                    'icon' => 'bi-arrow-left-right',
                    'color' => 'success'
                ];
            });

        $activities = $latestSiswa
            ->concat($latestBuku)
            ->concat($latestPinjam)
            ->sortByDesc('time')
            ->take(8);

        return view('admin.dashboard', compact(
            'siswaCount',
            'bukuCount',
            'pinjamCount',
            'activities'
        ));
    }

    public function indexSiswa()
    {
        $this->checkAdmin();

        $siswa = User::where('role', 'siswa')->with('siswa')->get();
        return view('admin.siswa.index', compact('siswa'));
    }

    public function storeSiswa(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'nis' => 'required|string|unique:users|regex:/^[0-9.]+$/',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'password' => 'required|string|size:6|alpha_num',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'role' => 'siswa',
                'password' => Hash::make($request->password),
            ]);

            \App\Models\Siswa::create([
                'nis' => $request->nis,
                'nama_siswa' => $request->name,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'password' => Hash::make($request->password),
            ]);
        });

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Akun siswa berhasil dibuat.');
    }

    public function updateSiswa(Request $request, $id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'nis' => 'required|string|regex:/^[0-9.]+$/|unique:users,nis,' . $id,
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
        ]);

        $oldNis = $user->nis;

        $user->name = $request->name;
        $user->nis = $request->nis;

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|size:6|alpha_num'
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sync ke tabel siswa
        $siswa = \App\Models\Siswa::where('nis', $oldNis)->first();

        if ($siswa) {
            $siswa->update([
                'nis' => $request->nis,
                'nama_siswa' => $request->name,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
            ]);

            if ($request->filled('password')) {
                $siswa->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        }

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroySiswa($id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        \App\Models\Siswa::where('nis', $user->nis)->delete();
        $user->delete();

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Akun siswa berhasil dihapus.');
    }
}
