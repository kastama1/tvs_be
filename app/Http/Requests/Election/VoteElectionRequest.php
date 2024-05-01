<?php

namespace App\Http\Requests\Election;

use App\Models\ElectionParty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class VoteElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $election = $this->route('election');

        return Gate::check('vote', $election);
    }
    public function rules(): array
    {
        return [
            'electionParty' => ['required', 'integer', Rule::exists(ElectionParty::class, 'id')],
        ];
    }
}
