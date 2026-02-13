<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StorePermitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Applicant data
            'nama_pemohon' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\.\,\-\']+$/u'],
            'nik_pemohon' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'alamat' => ['required', 'string', 'max:1000'],
            'nomor_telepon' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\s]+$/'],
            
            // Billboard data
            'klasifikasi' => ['required', 'in:permanen,non_permanen'],
            'ukuran_jumlah' => ['required', 'string', 'max:255'],
            'narasi' => ['required', 'string', 'max:2000'],
            
            // Location
            'lokasi_alamat' => ['required', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            
            // Documents
            'ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'npwp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'akte_pendirian' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'retribusi_pajak' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'data_pemohon' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'surat_pernyataan' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'foto_kondisi' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'gambar_konstruksi' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'surat_permohonan' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'surat_kuasa' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
            'nama_pemohon.regex' => 'Nama pemohon hanya boleh berisi huruf, spasi, dan tanda baca.',
            'nik_pemohon.required' => 'NIK pemohon wajib diisi.',
            'nik_pemohon.size' => 'NIK harus 16 digit.',
            'nik_pemohon.regex' => 'NIK hanya boleh berisi angka.',
            'nomor_telepon.regex' => 'Format nomor telepon tidak valid.',
            'latitude.between' => 'Koordinat latitude tidak valid.',
            'longitude.between' => 'Koordinat longitude tidak valid.',
            '*.mimes' => 'File :attribute harus berformat JPG, JPEG, PNG, atau PDF.',
            '*.max' => 'Ukuran file :attribute maksimal :max KB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_pemohon' => 'Nama Pemohon',
            'nik_pemohon' => 'NIK Pemohon',
            'nomor_telepon' => 'Nomor Telepon',
            'klasifikasi' => 'Klasifikasi Reklame',
            'ukuran_jumlah' => 'Ukuran/Jumlah',
            'narasi' => 'Narasi Reklame',
            'lokasi_alamat' => 'Alamat Lokasi',
            'ktp' => 'Fotokopi KTP',
            'npwp' => 'Fotokopi NPWP',
            'akte_pendirian' => 'Akte Pendirian',
            'retribusi_pajak' => 'Bukti Retribusi/Pajak',
            'data_pemohon' => 'Data Isian Pemohon',
            'surat_pernyataan' => 'Surat Pernyataan',
            'foto_kondisi' => 'Foto Kondisi Reklame',
            'gambar_konstruksi' => 'Gambar Konstruksi',
            'surat_permohonan' => 'Surat Permohonan',
            'surat_kuasa' => 'Surat Kuasa',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize text inputs
        $this->merge([
            'nama_pemohon' => $this->sanitizeString($this->nama_pemohon),
            'alamat' => $this->sanitizeString($this->alamat),
            'lokasi_alamat' => $this->sanitizeString($this->lokasi_alamat),
            'narasi' => $this->sanitizeString($this->narasi),
            'nomor_telepon' => preg_replace('/[^0-9\+\-\(\)\s]/', '', $this->nomor_telepon ?? ''),
            'nik_pemohon' => preg_replace('/[^0-9]/', '', $this->nik_pemohon ?? ''),
        ]);
    }

    /**
     * Sanitize string input.
     */
    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        // Remove potentially dangerous characters while keeping safe ones
        $value = strip_tags($value);
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
        
        return trim($value);
    }
}
