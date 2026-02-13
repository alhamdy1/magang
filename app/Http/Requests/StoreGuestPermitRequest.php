<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestPermitRequest extends FormRequest
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
            // Guest identification
            'guest_email' => ['required', 'email:rfc,dns', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\s]+$/'],
            'guest_nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            
            // Applicant information
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
            'doc_ktp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_npwp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_akte' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_retribusi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_data_isian' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_pernyataan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_foto_reklame' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_konstruksi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_permohonan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_kuasa' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guest_email.required' => 'Email wajib diisi untuk tracking.',
            'guest_email.email' => 'Format email tidak valid.',
            'guest_nik.required' => 'NIK wajib diisi untuk verifikasi.',
            'guest_nik.size' => 'NIK harus 16 digit.',
            'guest_nik.regex' => 'NIK hanya boleh berisi angka.',
            'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
            'nama_pemohon.regex' => 'Nama pemohon hanya boleh berisi huruf.',
            'nik_pemohon.required' => 'NIK pemohon wajib diisi.',
            'nik_pemohon.size' => 'NIK pemohon harus 16 digit.',
            'nik_pemohon.regex' => 'NIK pemohon hanya boleh berisi angka.',
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
            'guest_email' => 'Email',
            'guest_phone' => 'Nomor HP',
            'guest_nik' => 'NIK',
            'nama_pemohon' => 'Nama Pemohon',
            'nik_pemohon' => 'NIK Pemohon',
            'nomor_telepon' => 'Nomor Telepon',
            'klasifikasi' => 'Klasifikasi Reklame',
            'doc_ktp' => 'Fotokopi KTP',
            'doc_npwp' => 'Fotokopi NPWP',
            'doc_akte' => 'Akte Pendirian',
            'doc_retribusi' => 'Bukti Retribusi/Pajak',
            'doc_data_isian' => 'Data Isian Pemohon',
            'doc_pernyataan' => 'Surat Pernyataan',
            'doc_foto_reklame' => 'Foto Reklame',
            'doc_konstruksi' => 'Gambar Konstruksi',
            'doc_permohonan' => 'Surat Permohonan',
            'doc_kuasa' => 'Surat Kuasa',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_pemohon' => $this->sanitizeString($this->nama_pemohon),
            'alamat' => $this->sanitizeString($this->alamat),
            'lokasi_alamat' => $this->sanitizeString($this->lokasi_alamat),
            'narasi' => $this->sanitizeString($this->narasi),
            'guest_email' => strtolower(trim($this->guest_email ?? '')),
            'guest_phone' => preg_replace('/[^0-9\+\-\(\)\s]/', '', $this->guest_phone ?? ''),
            'guest_nik' => preg_replace('/[^0-9]/', '', $this->guest_nik ?? ''),
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
        
        $value = strip_tags($value);
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
        
        return trim($value);
    }
}
