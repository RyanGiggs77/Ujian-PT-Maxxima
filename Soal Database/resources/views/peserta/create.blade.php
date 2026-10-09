@extends('layouts.app')
@section('title', 'Tambah Peserta dan Nilai')
@section('content')
<div class="page-head"><div><p class="eyebrow">Data akademik</p><h1>Tambah Peserta dan Nilai</h1></div></div>
<div class="panel form-panel"><div class="panel-body">
    <form method="POST" action="{{ route('peserta.store') }}">
        @csrf
        <div class="field">
            <label for="nis">Siswa *</label>
            <select id="nis" name="nis" required>
                <option value="">Pilih Siswa</option>
                @foreach($daftarSiswa as $siswa)
                    <option value="{{ $siswa->nis }}" @selected((string) old('nis') === (string) $siswa->nis)>{{ $siswa->nis }} - {{ $siswa->nama }}</option>
                @endforeach
            </select>
            @if($daftarSiswa->isEmpty())<p class="hint">Tambahkan siswa terlebih dahulu.</p>@endif
        </div>
        <div class="field">
            <label for="id_ujian">Ujian *</label>
            <select id="id_ujian" name="id_ujian" required>
                <option value="">Pilih Ujian</option>
                @foreach($daftarUjian as $ujian)
                    <option value="{{ $ujian->id_ujian }}" @selected(old('id_ujian') == $ujian->id_ujian)>#{{ $ujian->id_ujian }} - {{ $ujian->nama_ujian }} / {{ $ujian->mataPelajaran->nama_matpel }} / {{ $ujian->tanggal->format('d/m/Y H:i') }}</option>
                @endforeach
            </select>
            @if($daftarUjian->isEmpty())<p class="hint">Tambahkan ujian terlebih dahulu.</p>@endif
        </div>
        <div class="field">
            <label for="nilai">Nilai (opsional)</label>
            <input id="nilai" name="nilai" type="number" min="0" max="100" step="0.01" value="{{ old('nilai') }}">
            <p class="hint">Rentang 0–100, maksimal 2 angka desimal. Kosongkan jika belum dinilai. KKM {{ \App\Models\Peserta::KKM }}.</p>
        </div>
        <div class="form-footer">
            <button type="submit">Simpan Data</button>
            <a class="button secondary" href="{{ route('peserta.index') }}">Batal</a>
        </div>
    </form>
</div></div>
@endsection
