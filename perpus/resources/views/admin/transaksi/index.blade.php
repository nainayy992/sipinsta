@extends('layouts.dashboard')

@section('title', 'Transaksi Perpustakaan')

@section('dashboard_content')
<div class="row">
    <div class="col-12">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4">Riwayat Transaksi Perpustakaan</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Siswa</th>
                            <th class="py-3">Buku</th>
                            <th class="py-3">Tgl Pinjam</th>
                            <th class="py-3">Tgl Kembali</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr>
                            <td>
                                <span class="fw-bold d-block">{{ $t->nama_siswa }}</span>
                                <small class="text-muted">NIS: {{ $t->nis }} | Kelas: {{ $t->kelas }} | Jurusan: {{ $t->jurusan }}</small>
                            </td>
                            <td>{{ $t->judul_buku }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $t->status === 'Dipinjam' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }}">
                                    {{ $t->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
