<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTicketRequest extends FormRequest
{
    public function authorize()
    {
        // Izinkan jika user memiliki permission untuk mengedit tiket
        return true;
    }

    public function rules()
    {
        // Aturan validasi HANYA untuk form assign
        return [
            'assignee' => 'required|exists:users,id',
            'sla_id'   => 'required|exists:slas,id',
        ];
    }
}
