@extends('layouts.dashboard')

@section('title', 'Peminjaman Saya')

@section('dashboard_content')
<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4">Riwayat Peminjaman</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="px-3">Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Maks Tanggal Kembali</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td class="px-3">
                        <span class="fw-bold d-block">{{ $item->judul_buku }}</span>
                        <small class="text-muted">Jumlah: {{ $item->jumlah }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $item->status === 'Dipinjam' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }} small">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($item->status === 'Dipinjam')
                        <button class="btn btn-sm btn-outline-maroon btn-kembalikan px-3" data-id="{{ $item->id_peminjaman }}">
                            <i class="bi bi-arrow-return-left me-1"></i> Kembalikan
                        </button>
                        @else
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                             Selesai
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                        <p class="text-muted">Belum ada riwayat peminjaman.</p>
                        <a href="{{ route('siswa.buku.cari') }}" class="btn btn-maroon btn-sm rounded-pill px-4">Cari Buku</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const returnButtons = document.querySelectorAll('.btn-kembalikan');

    returnButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: "Apakah Anda yakin ingin mengembalikan buku ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#662222',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/siswa/peminjaman/${id}/kembalikan`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#662222'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message,
                                confirmButtonColor: '#662222'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengembalikan buku.',
                            confirmButtonColor: '#662222'
                        });
                    });
                }
            });
        });
    });

});
</script>
@endpush
@endsection
