@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('content')
<div class="page-head"><div><p class="eyebrow">Data akademik</p><h1>Tambah Siswa</h1></div></div>
<div class="panel form-panel"><div class="panel-body">
    <form method="POST" action="{{ route('siswa.store') }}">
        @csrf
        <div class="field">
            <label for="nis">NIS *</label>
            <input id="nis" name="nis" type="text" maxlength="20" value="{{ old('nis') }}" required>
        </div>
        <div class="field">
            <label for="nama">Nama *</label>
            <input id="nama" name="nama" type="text" maxlength="50" value="{{ old('nama') }}" required>
        </div>
        <div class="field">
            <label for="alamat">Alamat *</label>
            <textarea id="alamat" name="alamat" rows="3" maxlength="100" required>{{ old('alamat') }}</textarea>
        </div>
        <div class="form-footer">
            <button type="submit">Simpan Data</button>
            <a class="button secondary" href="{{ route('siswa.index') }}">Batal</a>
        </div>
    </form>
</div></div>
@endsection
