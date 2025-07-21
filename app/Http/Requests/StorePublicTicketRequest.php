<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Izinkan semua orang mengakses ini
    }

    public function rules()
    {
        return [
            'guest_name'   => 'required|string|max:100',
            'guest_divisi' => 'required|string|max:100',
            'detail'       => 'required|string',
        ];
    }
}
