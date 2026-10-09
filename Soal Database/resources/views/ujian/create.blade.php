@extends('layouts.app')
@section('title', 'Tambah Ujian')
@section('content')
<div class="page-head"><div><p class="eyebrow">Data akademik</p><h1>Tambah Ujian</h1></div></div>
<div class="panel form-panel"><div class="panel-body">
    <form method="POST" action="{{ route('ujian.store') }}">
        @csrf
        <div class="field">
            <label for="nama_ujian">Nama Ujian *</label>
            <input id="nama_ujian" name="nama_ujian" type="text" maxlength="50" value="{{ old('nama_ujian') }}" required>
        </div>
        <div class="field">
            <label for="id_matpel">Mata Pelajaran *</label>
            <select id="id_matpel" name="id_matpel" required>
                <option value="">Pilih Mata Pelajaran</option>
                @foreach($daftarMataPelajaran as $mataPelajaran)
                    <option value="{{ $mataPelajaran->id_matpel }}" @selected(old('id_matpel') == $mataPelajaran->id_matpel)>{{ $mataPelajaran->nama_matpel }}</option>
                @endforeach
            </select>
            @if($daftarMataPelajaran->isEmpty())<p class="hint">Tambahkan mata pelajaran terlebih dahulu.</p>@endif
        </div>
        <div class="field">
            <label for="tanggal">Tanggal Ujian *</label>
            <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal') }}" required>
            <p class="hint">Pilih tanggal melalui kalender.</p>
        </div>
        <div class="field">
            <label for="waktu">Jam Ujian *</label>
            <input id="waktu" name="waktu" type="time" value="{{ old('waktu') }}" required>
        </div>
        <div class="form-footer">
            <button type="submit">Simpan Data</button>
            <a class="button secondary" href="{{ route('ujian.index') }}">Batal</a>
        </div>
    </form>
</div></div>
@endsection
