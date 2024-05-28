<?php

namespace App\Http\Requests\Candidate;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('update', Candidate::class);
    }

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
