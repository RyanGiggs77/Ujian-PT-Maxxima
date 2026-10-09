@extends('layouts.app')
@section('title', 'Laporan Akademik')
@section('content')
<div class="page-head"><div><p class="eyebrow">Ringkasan hasil belajar</p><h1>Laporan Akademik</h1>
    <p class="subtle">Jadwal ujian, jumlah peserta, dan hasil kelulusan siswa.</p></div>
    <a class="button secondary" href="{{ route('peserta.index') }}">Kelola Nilai</a>
</div>
<section class="panel">
    <div class="panel-head"><h2>Ujian dan jumlah peserta</h2></div>
    <form class="filter" method="GET" action="{{ route('laporan.index') }}">
        <div><label for="tanggal">Tanggal ujian (dd/mm/yyyy)</label><input type="text" id="tanggal" name="tanggal" value="{{ $tanggal }}" placeholder="dd/mm/yyyy" maxlength="10" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"></div>
        <button type="submit">Tampilkan</button><a class="button secondary" href="{{ route('laporan.index') }}">Semua Tanggal</a>
    </form>
    <div class="table-wrap"><table>
        <thead><tr><th>Nama Ujian</th><th>Nama Matpel</th><th>Tanggal</th><th>Jumlah Peserta</th><th>Jumlah Peserta Lulus</th></tr></thead>
        <tbody>@forelse($ujian as $item)
            <tr><td>{{ $item->nama_ujian }}</td><td>{{ $item->mataPelajaran->nama_matpel }}</td>
                <td>{{ $item->tanggal->format('d/m/Y H:i') }}</td><td>{{ $item->peserta_count }}</td><td>{{ $item->peserta_lulus_count }}</td></tr>
        @empty
            <tr><td colspan="5" class="empty">Tidak ada ujian{{ $tanggal ? ' pada tanggal ini' : ' yang tercatat' }}.</td></tr>
        @endforelse</tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $ujian])
</section>
<section class="panel">
    <div class="panel-head"><h2>Siswa lulus semua ujian{{ $tanggal ? ' pada '.$tanggal : '' }}</h2><span class="badge pass">KKM {{ \App\Models\Peserta::KKM }}</span></div>
    <div class="summary"><strong>{{ $jumlahLulusSemua }}</strong><div>Siswa memenuhi seluruh syarat kelulusan
        <p class="hint">Mengikuti seluruh ujian {{ $tanggal ? 'pada tanggal '.$tanggal : 'yang tercatat' }} dan memperoleh nilai ≥ {{ \App\Models\Peserta::KKM }} pada setiap ujian.</p></div></div>
    <div class="table-wrap"><table><thead><tr><th>NIS</th><th>Nama Siswa</th></tr></thead>
        <tbody>@forelse($lulusSemua as $siswa)
            <tr><td>{{ $siswa->nis }}</td><td>{{ $siswa->nama }}</td></tr>
        @empty
            <tr><td colspan="2" class="empty">Belum ada siswa yang lulus semua ujian{{ $tanggal ? ' pada tanggal ini' : '' }}.</td></tr>
        @endforelse</tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $lulusSemua])
</section>
<section class="panel">
    <div class="panel-head"><div><h2>Siswa tidak lulus</h2><p class="hint">Satu baris per siswa, dengan rincian seluruh ujian yang nilainya di bawah KKM. Nilai kosong belum dinilai.</p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>NIS</th><th>Nama Siswa</th><th>Ujian</th><th>Mata Pelajaran</th><th>Nilai</th><th>KKM</th></tr></thead>
        <tbody>@forelse($gagal as $siswa)
            <tr>
                <td>{{ $siswa->nis }}</td><td>{{ $siswa->nama }}</td>
                <td>@foreach($siswa->peserta as $peserta)<div>{{ $peserta->ujian->nama_ujian }}</div>@endforeach</td>
                <td>@foreach($siswa->peserta as $peserta)<div>{{ $peserta->ujian->mataPelajaran->nama_matpel }}</div>@endforeach</td>
                <td>@foreach($siswa->peserta as $peserta)<div>{{ $peserta->nilai }}</div>@endforeach</td>
                <td>{{ \App\Models\Peserta::KKM }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">Tidak ada nilai di bawah KKM.</td></tr>
        @endforelse</tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $gagal])
</section>
<p class="hint">Filter tanggal berlaku pada daftar ujian dan siswa lulus semua ujian. Laporan siswa tidak lulus mencakup seluruh ujian di database. Jika tidak ada ujian dalam cakupan yang dipilih, jumlah lulus semua adalah 0.</p>
@endsection
