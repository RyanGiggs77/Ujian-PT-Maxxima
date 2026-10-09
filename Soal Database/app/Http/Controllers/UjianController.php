<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UjianController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['tanggal' => ['nullable', 'date_format:d/m/Y']]);
        $tanggal = $data['tanggal'] ?? null;
        $tanggalDatabase = $tanggal ? Carbon::createFromFormat('!d/m/Y', $tanggal)->format('Y-m-d') : null;
        $daftarUjian = Ujian::with('mataPelajaran')->withCount('peserta')
            ->when($tanggalDatabase, fn ($query) => $query->whereDate('tanggal', $tanggalDatabase))
            ->orderBy('tanggal')->paginate(15)->withQueryString();

        return view('ujian.index', compact('daftarUjian', 'tanggal'));
    }

    public function create(): View
    {
        $daftarMataPelajaran = MataPelajaran::orderBy('nama_matpel')->get();

        return view('ujian.create', compact('daftarMataPelajaran'));
    }

    public function store(Request $request): RedirectResponse
    {
        Ujian::create($this->validated($request));

        return redirect()->route('ujian.index')->with('success', 'Ujian berhasil ditambahkan.');
    }

    public function edit(Ujian $ujian): View
    {
        $daftarMataPelajaran = MataPelajaran::orderBy('nama_matpel')->get();

        return view('ujian.edit', compact('ujian', 'daftarMataPelajaran'));
    }

    public function update(Request $request, Ujian $ujian): RedirectResponse
    {
        $ujian->update($this->validated($request));

        return redirect()->route('ujian.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Ujian $ujian): RedirectResponse
    {
        return $this->deleteAcademicRecord($ujian, 'ujian.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nama_ujian' => ['required', 'string', 'max:50'],
            'id_matpel' => ['required', 'integer', Rule::exists('mata_pelajaran', 'id_matpel')],
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'waktu' => ['required', 'date_format:H:i'],
        ]);
        $data['tanggal'] = Carbon::createFromFormat('!Y-m-d H:i', $data['tanggal'].' '.$data['waktu'])->format('Y-m-d H:i:s');
        unset($data['waktu']);

        return $data;
    }
}
