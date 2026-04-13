@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-pencil"></i> Edit Barang</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.barang.update', $barang->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Barang *</label>
                <input type="text" name="nama_barang" class="form-control" required value="{{ old('nama_barang', $barang->nama_barang) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Harga Sewa *</label>
                    <input type="number" name="harga_sewa" class="form-control" required min="0" step="0.01" value="{{ old('harga_sewa', $barang->harga_sewa) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stok *</label>
                    <input type="number" name="stok" class="form-control" required min="0" value="{{ old('stok', $barang->stok) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Foto Barang</label>
                @if($barang->foto)
                    <div class="mb-2">
                        <img src="{{ asset($barang->foto) }}" width="200" class="rounded">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
