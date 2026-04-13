@extends('layouts.dashboard')

@section('title', 'Cari Buku')

@section('dashboard_content')
<div class="card card-custom p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h5 class="fw-bold mb-1">Cari Koleksi Buku</h5>
            <p class="text-muted small mb-0">Temukan buku favoritmu di perpustakaan kami.</p>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 border-maroon rounded-start-pill ps-3">
                    <i class="bi bi-search text-maroon"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 border-maroon rounded-end-pill py-2" placeholder="Masukkan judul buku atau pengarang...">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <form action="{{ route('siswa.buku.cari') }}" method="GET" id="filterForm">
            <div class="card card-custom p-4">
                <h6 class="fw-bold mb-3">Filter Kategori</h6>
                @foreach($kategori as $cat)
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="kategori[]" value="{{ $cat->id_kategori }}" id="cat{{ $cat->id_kategori }}" 
                        {{ is_array(request('kategori')) && in_array($cat->id_kategori, request('kategori')) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="cat{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</label>
                </div>
                @endforeach
                <input type="hidden" name="search" id="hiddenSearchInput" value="{{ request('search') }}">
            </div>
        </form>
    </div>
    <div class="col-md-9">
        <div class="row g-4" id="bookResults">
            @forelse($buku as $item)
            <div class="col-md-4">
                <div class="card card-custom h-100 overflow-hidden border-0 book-card" data-id="{{ $item->id_buku }}" style="cursor: pointer;">
                    <div class="bg-light p-4 text-center">
                        @if($item->foto)
                            <img src="{{ asset('images/' . $item->foto) }}" alt="{{ $item->judul_buku }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                        @else
                            <i class="bi bi-book fs-1 text-maroon opacity-25"></i>
                        @endif
                    </div>
                    <div class="p-3">
                        <h6 class="fw-bold mb-1 text-truncate">{{ $item->judul_buku }}</h6>
                        <p class="small text-muted mb-2 text-truncate">{{ $item->pengarang }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge {{ $item->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} small">
                                {{ $item->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                            <span class="small text-muted">Stok: {{ $item->stok }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted opacity-25 d-block mb-3"></i>
                <p class="text-muted">Buku tidak ditemukan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Detail Buku (Same as Dashboard) -->
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
    // Category Filter Auto-Submit
    const checkboxes = document.querySelectorAll('.filter-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });

    // Search Bar Logic
    const searchInput = document.getElementById('searchInput');
    const hiddenSearchInput = document.getElementById('hiddenSearchInput');
    
    if(searchInput && hiddenSearchInput) {
        searchInput.value = hiddenSearchInput.value;
        
        searchInput.addEventListener('input', function() {
            hiddenSearchInput.value = this.value;
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    }

    // Book Detail Modal Logic
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
