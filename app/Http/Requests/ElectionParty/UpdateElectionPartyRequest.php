<?php

namespace App\Http\Requests\ElectionParty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionPartyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'campaign' => ['nullable', 'string'],
        ];
    }
}
