@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('dashboard_content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-4 border-maroon">
            <div class="d-flex align-items-center">
                <div class="bg-maroon bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-people fs-3 text-maroon"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Anggota</h6>
                    <h3 class="fw-bold mb-0 text-maroon">{{ $siswaCount }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.siswa.index') }}" class="stretched-link text-decoration-none mt-3 d-block small">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-4 border-primary">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-book fs-3 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Buku</h6>
                    <h3 class="fw-bold mb-0 text-primary">{{ $bukuCount }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.buku.index') }}" class="stretched-link text-decoration-none mt-3 d-block small">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-4 border-success">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-arrow-left-right fs-3 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Peminjaman Aktif</h6>
                    <h3 class="fw-bold mb-0 text-success">{{ $pinjamCount }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.transaksi.index') }}" class="stretched-link text-decoration-none mt-3 d-block small">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4">Aktivitas Terakhir</h5>
            <div class="activity-feed">
                @forelse($activities as $activity)
                    <div class="d-flex mb-4">
                        <div class="bg-{{ $activity['color'] }} bg-opacity-10 p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 small">{{ $activity['title'] }}</h6>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $activity['time']->diffForHumans() }}</span>
                            </div>
                            <p class="text-muted small mb-0">{{ $activity['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history fs-1 text-muted opacity-25 d-block mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas terbaru hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-4 shadow-sm border-0">
            <h5 class="fw-bold mb-3 text-maroon">Aksi Cepat</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.buku.index') }}" class="btn btn-light text-start border d-flex align-items-center p-3 rounded-3">
                    <i class="bi bi-plus-circle-fill text-maroon me-3 fs-5"></i>
                    <div>
                        <div class="fw-bold small">Tambah Buku Baru</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Input koleksi buku baru</div>
                    </div>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-light text-start border d-flex align-items-center p-3 rounded-3">
                    <i class="bi bi-person-plus-fill text-primary me-3 fs-5"></i>
                    <div>
                        <div class="fw-bold small">Daftar Anggota</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Tambah siswa baru</div>
                    </div>
                </a>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-light text-start border d-flex align-items-center p-3 rounded-3">
                    <i class="bi bi-file-earmark-pdf-fill text-success me-3 fs-5"></i>
                    <div>
                        <div class="fw-bold small">Laporan</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Cetak riwayat peminjaman</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
