<?php

namespace App\Http\Requests\Election;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AssignCandidatesElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $election = $this->route('election');

        return Gate::check('assignCandidates', $election);
    }
    public function rules(): array
    {
        return [
            'candidates' => ['nullable', 'array'],
            'canidates.*' => ['nullable', 'string', Rule::exists(Candidate::class, 'id')],
        ];
    }
}
