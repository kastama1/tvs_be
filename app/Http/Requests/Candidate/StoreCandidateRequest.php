<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'campaign' => ['required', 'string'],
            'images' => ['nullable', 'min:1', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'election_party_id' => ['nullable', 'integer', 'exists:election_parties,id'],
        ];
    }
}
