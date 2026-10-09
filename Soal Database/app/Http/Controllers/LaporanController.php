<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Siswa;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['tanggal' => ['nullable', 'date_format:d/m/Y']]);
        $tanggal = $data['tanggal'] ?? null;
        $tanggalDatabase = $tanggal ? Carbon::createFromFormat('!d/m/Y', $tanggal)->format('Y-m-d') : null;
        $ujian = Ujian::with('mataPelajaran')->withCount([
            'peserta',
            'peserta as peserta_lulus_count' => fn ($query) => $query->where('nilai', '>=', Peserta::KKM),
        ])
            ->when($tanggalDatabase, fn ($query) => $query->whereDate('tanggal', $tanggalDatabase))
            ->orderBy('tanggal')->paginate(15, ['*'], 'ujian_page')->withQueryString();

        $lulusQuery = Siswa::query()
            ->whereExists(function ($query) use ($tanggalDatabase) {
                $query->selectRaw('1')->from('ujian')
                    ->when($tanggalDatabase, fn ($query) => $query->whereDate('tanggal', $tanggalDatabase));
            })
            ->whereNotExists(function ($query) use ($tanggalDatabase) {
                $query->selectRaw('1')->from('ujian as u')
                    ->when($tanggalDatabase, fn ($query) => $query->whereDate('u.tanggal', $tanggalDatabase))
                    ->whereNotExists(function ($peserta) {
                        $peserta->selectRaw('1')->from('peserta as p')
                            ->whereColumn('p.id_ujian', 'u.id_ujian')
                            ->whereColumn('p.nis', 'siswa.nis')
                            ->where('p.nilai', '>=', Peserta::KKM);
                    });
            });
        $jumlahLulusSemua = (clone $lulusQuery)->count();
        $lulusSemua = $lulusQuery->orderBy('nama')->paginate(15, ['*'], 'lulus_page')->withQueryString();
        $gagal = Siswa::whereHas('peserta', fn ($query) => $query->where('nilai', '<', Peserta::KKM))
            ->with(['peserta' => fn ($query) => $query
                ->where('nilai', '<', Peserta::KKM)
                ->with('ujian.mataPelajaran')->orderBy('id_ujian')])
            ->orderBy('nama')->orderBy('nis')
            ->paginate(15, ['*'], 'gagal_page')->withQueryString();

        return view('laporan.index', compact('tanggal', 'ujian', 'jumlahLulusSemua', 'lulusSemua', 'gagal'));
    }
}
