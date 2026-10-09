@extends('layouts.app')
@section('title', 'Peserta dan Nilai')
@section('content')
<div class="page-head">
    <div><p class="eyebrow">Data akademik</p><h1>Peserta dan Nilai</h1><p class="subtle">Kelola data peserta dan nilai yang tercatat.</p></div>
    <a class="button" href="{{ route('peserta.create') }}">+ Tambah Data</a>
</div>
<p class="subtle" style="margin-bottom:18px">Edit peserta untuk mengisi nilai. Nilai ≥ {{ \App\Models\Peserta::KKM }} lulus; nilai kosong belum dinilai.</p>
<div class="panel">
    <div class="table-wrap"><table>
        <thead><tr><th>NIS</th><th>Siswa</th><th>Ujian</th><th>Mata Pelajaran</th><th>Nilai</th><th>Kelulusan</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($daftarPeserta as $peserta)
            <tr>
                <td>{{ $peserta->nis }}</td><td>{{ $peserta->siswa->nama }}</td>
                <td>{{ $peserta->ujian->nama_ujian }}</td><td>{{ $peserta->ujian->mataPelajaran->nama_matpel }}</td>
                <td>{{ $peserta->nilai ?? '—' }}</td>
                <td><span @class(['badge', 'pass' => $peserta->status_kelulusan === 'Lulus', 'fail' => $peserta->status_kelulusan === 'Tidak lulus'])>{{ $peserta->status_kelulusan }}</span></td>
                <td><div class="actions">
                    <a href="{{ route('peserta.edit', $peserta->id) }}">Edit</a>
                    <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="delete" type="submit">Hapus</button>
                    </form>
                </div></td>
            </tr>
        @empty
            <tr><td class="empty" colspan="7">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $daftarPeserta])
</div>
@endsection
