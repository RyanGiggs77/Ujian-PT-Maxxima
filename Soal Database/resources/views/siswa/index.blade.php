@extends('layouts.app')
@section('title', 'Siswa')
@section('content')
<div class="page-head">
    <div><p class="eyebrow">Data akademik</p><h1>Siswa</h1><p class="subtle">Kelola data siswa yang tercatat.</p></div>
    <a class="button" href="{{ route('siswa.create') }}">+ Tambah Data</a>
</div>
<div class="panel">
    <div class="table-wrap"><table>
        <thead><tr><th>NIS</th><th>Nama</th><th>Alamat</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($daftarSiswa as $siswa)
            <tr>
                <td>{{ $siswa->nis }}</td><td>{{ $siswa->nama }}</td><td class="wrap">{{ $siswa->alamat }}</td>
                <td><div class="actions">
                    <a href="{{ route('siswa.edit', $siswa->nis) }}">Edit</a>
                    <form action="{{ route('siswa.destroy', $siswa->nis) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="delete" type="submit">Hapus</button>
                    </form>
                </div></td>
            </tr>
        @empty
            <tr><td class="empty" colspan="4">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @include('akademik.pagination', ['paginator' => $daftarSiswa])
</div>
@endsection
