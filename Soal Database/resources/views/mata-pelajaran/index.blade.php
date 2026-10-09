@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('content')
<div class="page-head">
    <div><p class="eyebrow">Data akademik</p><h1>Mata Pelajaran</h1><p class="subtle">Kelola data mata pelajaran yang tercatat.</p></div>
    <a class="button" href="{{ route('mata-pelajaran.create') }}">+ Tambah Data</a>
</div>
<div class="panel">
    <div class="table-wrap"><table>
        <thead><tr><th>Nama Mata Pelajaran</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($daftarMataPelajaran as $mataPelajaran)
            <tr>
                <td>{{ $mataPelajaran->nama_matpel }}</td>
                <td><div class="actions">
                    <a href="{{ route('mata-pelajaran.edit', $mataPelajaran->id_matpel) }}">Edit</a>
                    <form action="{{ route('mata-pelajaran.destroy', $mataPelajaran->id_matpel) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="delete" type="submit">Hapus</button>
                    </form>
                </div></td>
            </tr>
        @empty
            <tr><td class="empty" colspan="2">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $daftarMataPelajaran])
</div>
@endsection
