@extends('layouts.app')
@section('title', 'Ujian')
@section('content')
<div class="page-head">
    <div><p class="eyebrow">Data akademik</p><h1>Ujian</h1><p class="subtle">Kelola data ujian yang tercatat.</p></div>
    <a class="button" href="{{ route('ujian.create') }}">+ Tambah Data</a>
</div>
<div class="panel">
    <form class="filter" action="{{ route('ujian.index') }}" method="GET">
        <div><label for="tanggal">Tanggal ujian (dd/mm/yyyy)</label>
            <input type="text" id="tanggal" name="tanggal" value="{{ $tanggal }}" placeholder="dd/mm/yyyy" maxlength="10" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"></div>
        <button type="submit">Tampilkan</button><a class="button secondary" href="{{ route('ujian.index') }}">Semua Tanggal</a>
    </form>
    <div class="table-wrap"><table>
        <thead><tr><th>Nama Ujian</th><th>Mata Pelajaran</th><th>Tanggal</th><th>Jumlah Peserta</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($daftarUjian as $ujian)
            <tr>
                <td>{{ $ujian->nama_ujian }}</td><td>{{ $ujian->mataPelajaran->nama_matpel }}</td>
                <td>{{ $ujian->tanggal->format('d/m/Y H:i') }}</td><td>{{ $ujian->peserta_count }}</td>
                <td><div class="actions">
                    <a href="{{ route('ujian.edit', $ujian->id_ujian) }}">Edit</a>
                    <form action="{{ route('ujian.destroy', $ujian->id_ujian) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="delete" type="submit">Hapus</button>
                    </form>
                </div></td>
            </tr>
        @empty
            <tr><td class="empty" colspan="5">Belum ada data{{ $tanggal ? ' pada tanggal ini' : '' }}.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $daftarUjian])
</div>
@endsection
