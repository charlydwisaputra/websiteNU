<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul agenda wajib diisi.',
            'judul.max' => 'Judul agenda tidak boleh melebihi 255 karakter.',
            'tanggal_mulai.required' => 'Tanggal dan waktu mulai agenda wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal dan waktu tidak valid.',
            'lokasi.required' => 'Lokasi agenda wajib diisi.',
            'lokasi.max' => 'Lokasi tidak boleh melebihi 255 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh melebihi 5000 karakter.',
        ];
    }
}
