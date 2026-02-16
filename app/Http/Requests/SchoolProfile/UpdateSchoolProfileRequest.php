<?php

namespace App\Http\Requests\SchoolProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/*
|--------------------------------------------------------------------------
| Update School Profile Request
|--------------------------------------------------------------------------
|
| Validasi untuk update profil sekolah. NPSN harus 8 digit angka,
| logo maksimal 2MB (jpg/png/webp), dan field wajib diisi.
|
*/

class UpdateSchoolProfileRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'npsn' => [
                'required',
                'string',
                'size:8',
                'regex:/^\d{8}$/',
                'unique:schools,npsn,'.$this->route('school')?->id,
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'mission' => ['nullable', 'string', 'max:2000'],
            'logo' => [
                'nullable',
                File::image()
                    ->max(2048),
                'mimes:jpg,jpeg,png,webp',
            ],
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama sekolah wajib diisi.',
            'npsn.required' => 'NPSN wajib diisi.',
            'npsn.size' => 'NPSN harus terdiri dari 8 digit.',
            'npsn.regex' => 'NPSN harus berupa 8 digit angka.',
            'npsn.unique' => 'NPSN sudah terdaftar di sekolah lain.',
            'email.email' => 'Format email tidak valid.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'logo.image' => 'Logo harus berupa file gambar.',
        ];
    }
}
