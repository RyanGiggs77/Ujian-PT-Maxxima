@extends('layouts.app')
@section('title', 'Tambah Mata Pelajaran')
@section('content')
<div class="page-head"><div><p class="eyebrow">Data akademik</p><h1>Tambah Mata Pelajaran</h1></div></div>
<div class="panel form-panel"><div class="panel-body">
    <form method="POST" action="{{ route('mata-pelajaran.store') }}">
        @csrf
        <div class="field">
            <label for="nama_matpel">Nama Mata Pelajaran *</label>
            <input id="nama_matpel" name="nama_matpel" type="text" maxlength="50" value="{{ old('nama_matpel') }}" required>
        </div>
        <div class="form-footer">
            <button type="submit">Simpan Data</button>
            <a class="button secondary" href="{{ route('mata-pelajaran.index') }}">Batal</a>
        </div>
    </form>
</div></div>
@endsection
