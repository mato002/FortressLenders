<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'phone_primary' => ['nullable', 'string', 'max:50'],
            'phone_secondary' => ['nullable', 'string', 'max:50'],
            'accent_color' => ['required', 'string', 'max:50'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        $data['is_active'] = $this->boolean('is_active');

        return $data;
    }
}

