@extends('layouts.admin')

@section('title', 'Edit Paket')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-pencil"></i> Edit Paket</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.paket.update', $paket->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Paket *</label>
                <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                @error('nama_paket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Harga Total *</label>
                <input type="number" name="harga_total" class="form-control @error('harga_total') is-invalid @enderror" value="{{ old('harga_total', $paket->harga_total) }}" required min="0" step="0.01">
                @error('harga_total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Foto Paket</label>
                @if($paket->foto)
                    <div class="mb-2">
                        <img src="{{ asset($paket->foto) }}" width="200" class="rounded">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Barang-barang dalam Paket</label>
                <div id="barang-container">
                    @if($paket->barangs->count() > 0)
                        @foreach($paket->barangs as $index => $barangPaket)
                        <div class="barang-item mb-2">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <select name="barangs[]" class="form-select">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" {{ $barangPaket->id == $barang->id ? 'selected' : '' }}>
                                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1" value="{{ $barangPaket->pivot->jumlah }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-barang" style="{{ $index == 0 && $paket->barangs->count() == 1 ? 'display:none;' : '' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="barang-item mb-2">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <select name="barangs[]" class="form-select">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1" value="1">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-barang" style="display:none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-success mt-2" id="btn-add-barang">
                    <i class="bi bi-plus-circle"></i> Tambah Barang
                </button>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('barang-container');
    const btnAdd = document.getElementById('btn-add-barang');
    
    // Template untuk barang item baru
    const barangTemplate = `
        <div class="barang-item mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <select name="barangs[]" class="form-select">
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1" value="1">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-remove-barang">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Tambah barang baru
    btnAdd.addEventListener('click', function() {
        container.insertAdjacentHTML('beforeend', barangTemplate);
        updateRemoveButtons();
    });
    
    // Hapus barang
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-barang') || e.target.closest('.btn-remove-barang')) {
            const item = e.target.closest('.barang-item');
            item.remove();
            updateRemoveButtons();
        }
    });
    
    // Update visibility tombol remove
    function updateRemoveButtons() {
        const items = container.querySelectorAll('.barang-item');
        items.forEach((item, index) => {
            const btnRemove = item.querySelector('.btn-remove-barang');
            if (items.length > 1) {
                btnRemove.style.display = 'block';
            } else {
                btnRemove.style.display = 'none';
            }
        });
    }
    
    updateRemoveButtons();
});
</script>
@endpush
@endsection
