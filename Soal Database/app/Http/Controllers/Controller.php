<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    protected function deleteAcademicRecord(Model $record, string $route): RedirectResponse
    {
        try {
            $record->delete();
        } catch (QueryException $exception) {
            if (in_array($exception->errorInfo[0] ?? null, ['23503', '23000'], true)) {
                return back()->withErrors(['database' => 'Data masih digunakan. Hapus data peserta atau ujian terkait terlebih dahulu.']);
            }

            throw $exception;
        }

        return redirect()->route($route)->with('success', 'Data berhasil dihapus.');
    }
}
