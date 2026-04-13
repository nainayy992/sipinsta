@extends('layouts.dashboard')

@section('title', 'Siswa Dashboard')

@section('dashboard_content')
<div class="row g-4">
    <!-- Statistik Siswa -->
    <div class="col-md-6">
        <div class="card card-custom p-4 border-start border-4 border-maroon h-100">
            <h6 class="text-muted mb-3">Peminjaman Berlangsung</h6>
            <div class="d-flex align-items-center">
                <i class="bi bi-journal-check fs-1 text-maroon me-3"></i>
                <h2 class="fw-bold mb-0">{{ $activeLoansCount }}</h2>
                <span class="ms-2 text-muted mt-2">Buku sedang dipinjam</span>
            </div>
            <p class="mt-3 small text-muted mb-0">Pastikan untuk mengembalikan buku tepat waktu agar tidak terkena denda.</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom p-4 border-start border-4 border-primary h-100">
            <h6 class="text-muted mb-3">Akses Cepat</h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('siswa.buku.cari') }}" class="btn btn-light w-100 py-3 border">
                        <i class="bi bi-search d-block fs-4 mb-1 text-primary"></i>
                        <span class="small fw-bold">Cari Buku</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('siswa.peminjaman') }}" class="btn btn-light w-100 py-3 border">
                        <i class="bi bi-clock-history d-block fs-4 mb-1 text-success"></i>
                        <span class="small fw-bold">Riwayat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom p-4 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Rekomendasi Buku</h5>
        <a href="{{ route('siswa.buku.cari') }}" class="text-maroon text-decoration-none small fw-bold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>

    <!-- Dynamic Buku dari Controller -->
    <div class="row g-4">
        @foreach($recommendedBooks as $book)
        <div class="col-md-3">
            <div class="card border-0 bg-light rounded-4 overflow-hidden h-100 book-card" data-id="{{ $book->id_buku }}" style="cursor: pointer;">
                <div class="p-4 text-center">
                    @if($book->foto)
                        <img src="{{ asset('images/' . $book->foto) }}" alt="{{ $book->judul_buku }}" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                    @else
                        <i class="bi bi-book-half fs-1 text-muted opacity-50"></i>
                    @endif
                </div>
                <div class="p-3">
                    <h6 class="fw-bold mb-1 text-truncate">{{ $book->judul_buku }}</h6>
                    <p class="small text-muted mb-2">{{ $book->pengarang }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge {{ $book->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} small">
                            {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                        <span class="small text-muted">Stok: {{ $book->stok }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Detail Buku -->
<div class="modal fade" id="bookDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <img id="modalBookFoto" src="" alt="" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                </div>
                <h4 id="modalBookTitle" class="fw-bold text-center mb-1"></h4>
                <p id="modalBookAuthor" class="text-muted text-center mb-4"></p>
                
                <div class="bg-light p-3 rounded-4">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Penerbit</small>
                            <span id="modalBookPenerbit" class="fw-bold small"></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Tahun Terbit</small>
                            <span id="modalBookTahun" class="fw-bold small"></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Kategori</small>
                            <span id="modalBookKategori" class="fw-bold small"></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Stok</small>
                            <span id="modalBookStok" class="fw-bold small"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button id="modalPinjamBtn" class="btn btn-maroon w-100 py-3 rounded-pill fw-bold">Pinjam Sekarang</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookCards = document.querySelectorAll('.book-card');
    const modal = new bootstrap.Modal(document.getElementById('bookDetailModal'));
    const modalPinjamBtn = document.getElementById('modalPinjamBtn');
    let currentBookId = null;

    bookCards.forEach(card => {
        card.addEventListener('click', function() {
            currentBookId = this.getAttribute('data-id');
            fetch(`/siswa/buku/${currentBookId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalBookTitle').innerText = data.judul_buku;
                    document.getElementById('modalBookAuthor').innerText = data.pengarang;
                    document.getElementById('modalBookPenerbit').innerText = data.penerbit;
                    document.getElementById('modalBookTahun').innerText = data.tahun_terbit;
                    document.getElementById('modalBookKategori').innerText = data.kategori.nama_kategori;
                    document.getElementById('modalBookStok').innerText = data.stok;
                    document.getElementById('modalBookFoto').src = data.foto ? `/images/${data.foto}` : '';
                    
                    // Disable button if stock is 0
                    if (data.stok <= 0) {
                        modalPinjamBtn.disabled = true;
                        modalPinjamBtn.innerText = 'Stok Habis';
                    } else {
                        modalPinjamBtn.disabled = false;
                        modalPinjamBtn.innerText = 'Pinjam Sekarang';
                    }
                    
                    modal.show();
                });
        });
    });

    modalPinjamBtn.addEventListener('click', function() {
        if (!currentBookId) return;

        fetch(`/siswa/buku/${currentBookId}/pinjam`, {
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
                    title: 'Oops...',
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
                text: 'Terjadi kesalahan saat meminjam buku.',
                confirmButtonColor: '#662222'
            });
        });
    });
});
</script>
@endpush
@endsection
