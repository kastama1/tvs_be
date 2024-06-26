<?php

namespace App\Http\Requests\Election;

use App\Models\Election;
use App\Models\ElectionParty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AssignElectionPartiesElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('assignElectionParties', Election::class);
    }
    public function rules(): array
    {
        return [
            'election_parties' => ['nullable', 'array'],
            'election_parties.*' => ['nullable', 'string', Rule::exists(ElectionParty::class, 'id')],
        ];
    }
}
