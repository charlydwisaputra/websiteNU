<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'banom_id' => 'nullable|exists:banoms,id',
            'region' => 'nullable|string|max:255',
            'tampil_wilayah' => 'nullable|boolean',
            'status' => 'required|in:Aktif,aktif,Nonaktif,nonaktif,Alumni,alumni,Pending,pending',
            'is_public' => 'required|boolean',
            'is_pengurus' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
            'joined_at' => 'nullable|date',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.required' => 'Status keanggotaan wajib diisi.',
            'banom_id.exists' => 'Badan otonom yang dipilih tidak valid.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
