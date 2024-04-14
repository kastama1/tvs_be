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
            'images' => ['nullable', 'min:1', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
