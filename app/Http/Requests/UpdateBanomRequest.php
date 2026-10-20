<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBanomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'singkatan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:1000',
            'koordinator_id' => 'nullable|exists:anggota,id',
            'urutan' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama badan otonom wajib diisi.',
            'singkatan.required' => 'Singkatan / label badan otonom wajib diisi.',
            'koordinator_id.exists' => 'Koordinator yang dipilih tidak valid.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
