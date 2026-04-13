<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}

/* AdminBukuController.php
<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        $kategori = Kategori::all();
        return view('admin.buku.index', compact('buku', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:0',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images'), $imageName);
            $data['foto'] = $imageName;
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Delete old photo if it exists and isn't a default one
            if ($buku->foto && file_exists(public_path('images/' . $buku->foto))) {
                // Should check if it's one of the seeded defaults before deleting? 
                // For simplicity, we'll just leave them for now or delete if uploaded.
                @unlink(public_path('images/' . $buku->foto));
            }

            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images'), $imageName);
            $data['foto'] = $imageName;
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        
        if ($buku->foto && file_exists(public_path('images/' . $buku->foto))) {
             @unlink(public_path('images/' . $buku->foto));
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}

/* AdminController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $siswaCount = User::where('role', 'siswa')->count();
        $bukuCount = \App\Models\Buku::count(); 
        $pinjamCount = \Illuminate\Support\Facades\DB::table('peminjaman')->where('status', 'Dipinjam')->count();

        // Latest Activities (Merged from multiple tables) - FILTERED TO TODAY
        $latestSiswa = User::where('role', 'siswa')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'siswa',
                    'title' => 'Anggota Baru',
                    'description' => 'Siswa ' . $item->name . ' telah terdaftar.',
                    'time' => $item->created_at,
                    'icon' => 'bi-person-plus',
                    'color' => 'primary'
                ];
            });

        $latestBuku = \App\Models\Buku::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'buku',
                    'title' => 'Buku Baru',
                    'description' => 'Buku "' . $item->judul_buku . '" telah ditambahkan.',
                    'time' => $item->created_at,
                    'icon' => 'bi-book',
                    'color' => 'maroon'
                ];
            });

        $latestPinjam = \Illuminate\Support\Facades\DB::table('peminjaman')
            ->join('siswa', 'peminjaman.id_siswa', '=', 'siswa.id_siswa')
            ->whereDate('peminjaman.created_at', today())
            ->orderBy('peminjaman.created_at', 'desc')
            ->take(5)
            ->select('peminjaman.*', 'siswa.nama_siswa')
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'transaksi',
                    'title' => 'Transaksi Baru',
                    'description' => $item->nama_siswa . ' telah meminjam buku.',
                    'time' => \Carbon\Carbon::parse($item->created_at),
                    'icon' => 'bi-arrow-left-right',
                    'color' => 'success'
                ];
            });

        $activities = $latestSiswa->concat($latestBuku)->concat($latestPinjam)
            ->sortByDesc('time')
            ->take(8);

        return view('admin.dashboard', compact('siswaCount', 'bukuCount', 'pinjamCount', 'activities'));
    }

    public function indexSiswa()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $siswa = User::where('role', 'siswa')->with('siswa')->get();
        return view('admin.siswa.index', compact('siswa'));
    }

    public function storeSiswa(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'nis' => 'required|string|unique:users|regex:/^[0-9.]+$/',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'password' => 'required|string|size:6|alpha_num',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            User::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'role' => 'siswa',
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);

            \App\Models\Siswa::create([
                'nis' => $request->nis,
                'nama_siswa' => $request->name,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Akun siswa berhasil dibuat.');
    }

    public function updateSiswa(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'nis' => 'required|string|regex:/^[0-9.]+$/|unique:users,nis,' . $id,
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
        ]);

        $user->name = $request->name;
        $user->nis = $request->nis;
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|size:6|alpha_num']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sync with Siswa table
        $siswa = \App\Models\Siswa::where('nis', $user->getOriginal('nis'))->first();
        if ($siswa) {
            $siswa->update([
                'nis' => $request->nis,
                'nama_siswa' => $request->name,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
            ]);
            if ($request->filled('password')) {
                $siswa->update(['password' => Hash::make($request->password)]);
            }
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroySiswa($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);
        \App\Models\Siswa::where('nis', $user->nis)->delete();
        $user->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Akun siswa berhasil dihapus.');
    }
}

/* AdminTransaksiController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

/* AuthController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRoleSelection()
    {
        return view('auth.role_selection');
    }

    public function showAdminLogin()
    {
        return view('auth.login_admin');
    }

    public function showSiswaLogin()
    {
        return view('auth.login_siswa');
    }

    public function showRegisterAdmin()
    {
        return view('auth.register_admin');
    }

    public function login(Request $request)
    {
        if ($request->has('username')) {

            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $loginValue = $request->username;

        } elseif ($request->has('nis')) {

            $request->validate([
                'nis' => 'required|string',
                'password' => 'required|string'
            ]);

            $loginType = 'nis';
            $loginValue = $request->nis;

        } else {
            return back()->withErrors(['login_failed' => 'Login tidak valid']);
        }

        \Illuminate\Support\Facades\Log::info('Login attempt', [
            'loginType' => $loginType,
            'loginValue' => $loginValue,
            'session_id' => $request->session()->getId()
        ]);

        // Attempt login with basic credentials first (email/username/nis + password)
        if (Auth::attempt([$loginType => $loginValue, 'password' => $request->password])) {
            $user = Auth::user();
            \Illuminate\Support\Facades\Log::info('Login success', [
                'user_id' => $user->id,
                'role' => $user->role,
                'new_session_id' => $request->session()->getId()
            ]);

            // Check if user has the correct role for the login they are using
            if ($request->has('username') && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['login_failed' => 'Akun ini bukan akun Admin.']);
            }

            if ($request->has('nis') && $user->role !== 'siswa') {
                Auth::logout();
                return back()->withErrors(['login_failed' => 'Akun ini bukan akun Siswa.']);
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                \Illuminate\Support\Facades\Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            }

            \Illuminate\Support\Facades\Log::info('Redirecting to siswa dashboard');
            return redirect()->route('siswa.dashboard');
        }

        \Illuminate\Support\Facades\Log::warning('Login failed for user', ['loginValue' => $loginValue]);
        throw ValidationException::withMessages([
            'login_failed' => __('auth.failed'),
        ]);
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|alpha_num',
        ]);

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'username' => $request->username,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login.admin')
            ->with('success', 'Registrasi berhasil! Silahkan login.');
    }

    public function createSiswa(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:users',
            'password' => 'required|min:6|alpha_num'
        ]);

        User::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'role' => 'siswa',
            'password' => Hash::make($request->password),
        ]);

        \App\Models\Siswa::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->name,
            'kelas' => '-',
            'jurusan' => '-',
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun siswa berhasil dibuat');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
    
/* BukuController.php
<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
   
    public function dashboard()
    {
        // 4 Specific books requested by user
        $recommendedBooks = Buku::whereIn('foto', [
            'bukan 350 tahun.jpg',
            'home for allie.jpg',
            'van der wijck.jpg',
            'hujan.jpg'
        ])->get();

        // Count active loans for the current student
        $user = auth()->user();
        $siswa = \App\Models\Siswa::where('nis', $user->nis)->first();
        $activeLoansCount = 0;
        
        if ($siswa) {
            $activeLoansCount = \Illuminate\Support\Facades\DB::table('peminjaman')
                ->where('id_siswa', $siswa->id_siswa)
                ->where('status', 'Dipinjam')
                ->count();
        }

        return view('siswa.dashboard', compact('recommendedBooks', 'activeLoansCount'));
    }

    
    public function cari(Request $request)
    {
        $query = Buku::query();

        // Search by title or author
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('kategori') && is_array($request->kategori)) {
            $query->whereIn('id_kategori', $request->kategori);
        }

        $buku = $query->with('kategori')->get();
        $kategori = Kategori::whereIn('nama_kategori', ['Fiksi', 'Sejarah'])->get();

        return view('siswa.cari_buku', compact('buku', 'kategori'));
    }

    
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return response()->json($buku);
    }

    
    public function pinjam(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku habis.'], 400);
        }

        // Get authenticated user (Siswa)
        $user = auth()->user();
        $siswa = \App\Models\Siswa::where('nis', $user->nis)->first();

        if (!$siswa) {
            $user_info = $user ? "ID: {$user->id}, NIS: [{$user->nis}]" : "NULL";
            $siswa_exists = \App\Models\Siswa::where('nis', $user->nis)->exists();
            \Illuminate\Support\Facades\Log::error("Borrowing Error - User: $user_info, Siswa Exists in DB: " . ($siswa_exists ? 'YES' : 'NO'));
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan.'], 404);
        }

        // Check if student already has an active loan for this book
        $existingLoan = \Illuminate\Support\Facades\DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->where('peminjaman.id_siswa', $siswa->id_siswa)
            ->where('detail_peminjaman.id_buku', $id)
            ->where('peminjaman.status', 'Dipinjam')
            ->exists();

        if ($existingLoan) {
            return response()->json(['success' => false, 'message' => 'Anda sudah meminjam buku ini dan belum dikembalikan.'], 400);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($buku, $siswa) {
            // 1. Create Peminjaman record
            $peminjamanId = \Illuminate\Support\Facades\DB::table('peminjaman')->insertGetId([
                'id_siswa' => $siswa->id_siswa,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(7), // Default 7 days
                'status' => 'Dipinjam',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Create Detail Peminjaman
            \Illuminate\Support\Facades\DB::table('detail_peminjaman')->insert([
                'id_peminjaman' => $peminjamanId,
                'id_buku' => $buku->id_buku,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Update Book Stock
            $buku->decrement('stok');
        });

        return response()->json(['success' => true, 'message' => 'Buku berhasil dipinjam.']);
    }

  
    public function peminjaman()
    {
        $user = auth()->user();
        $siswa = \App\Models\Siswa::where('nis', $user->nis)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $peminjaman = \Illuminate\Support\Facades\DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('buku', 'detail_peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_siswa', $siswa->id_siswa)
            ->select('peminjaman.*', 'buku.judul_buku', 'buku.id_buku', 'detail_peminjaman.jumlah')
            ->orderBy('peminjaman.created_at', 'desc')
            ->get();

        return view('siswa.peminjaman', compact('peminjaman'));
    }

   
    public function kembalikan(Request $request, $id_peminjaman)
    {
        $peminjaman = \Illuminate\Support\Facades\DB::table('peminjaman')
            ->where('id_peminjaman', $id_peminjaman)
            ->first();

        if (!$peminjaman || $peminjaman->status === 'Dikembalikan') {
            return response()->json(['success' => false, 'message' => 'Data peminjaman tidak valid atau sudah dikembalikan.'], 400);
        }

        $detail = \Illuminate\Support\Facades\DB::table('detail_peminjaman')
            ->where('id_peminjaman', $id_peminjaman)
            ->first();

        if (!$detail) {
            return response()->json(['success' => false, 'message' => 'Detail peminjaman tidak ditemukan.'], 404);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($id_peminjaman, $detail) {
            // 1. Update status
            \Illuminate\Support\Facades\DB::table('peminjaman')
                ->where('id_peminjaman', $id_peminjaman)
                ->update(['status' => 'Dikembalikan', 'updated_at' => now()]);

            // 2. Increase stock
            Buku::where('id_buku', $detail->id_buku)->increment('stok');
        });

        return response()->json(['success' => true, 'message' => 'Buku berhasil dikembalikan.']);
    }
}

/* LandingController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function about()
    {
        return redirect()->route('home', ['#about']);
    }
}

