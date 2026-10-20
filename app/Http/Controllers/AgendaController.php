<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Tampilkan daftar agenda kegiatan dengan pencarian dan filter status tanggal.
     */
    public function index(Request $request): View
    {
        $query = Agenda::query();

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->string('search')->toString());
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'upcoming') {
                $query->where('tanggal_mulai', '>=', now());
            } elseif ($request->filter === 'past') {
                $query->where('tanggal_mulai', '<', now());
            }
        }

        $agendas = $query->orderBy('tanggal_mulai', 'desc')->paginate(10)->withQueryString();

        return view('admin.agenda.index', compact('agendas'));
    }

    /**
     * Tampilkan form penambahan agenda baru.
     */
    public function create(): View
    {
        return view('admin.agenda.create');
    }

    /**
     * Simpan agenda baru ke database.
     */
    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        Agenda::create($request->validated());

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda kegiatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah agenda kegiatan.
     */
    public function edit(int $id): View
    {
        $agenda = Agenda::findOrFail($id);

        return view('admin.agenda.edit', compact('agenda'));
    }

    /**
     * Perbarui data agenda kegiatan yang ada.
     */
    public function update(UpdateAgendaRequest $request, int $id): RedirectResponse
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->update($request->validated());

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data agenda kegiatan dari database.
     */
    public function destroy(int $id): RedirectResponse
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda kegiatan berhasil dihapus.');
    }
}
