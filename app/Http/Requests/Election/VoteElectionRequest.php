<?php

namespace App\Http\Requests\Election;

use App\Models\Candidate;
use App\Models\Election;
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
        /** @var $election Election */
        $election = $this->route('election');

        $rules = [
            'vote' => ['required', 'integer', Rule::exists($election->votableType, 'id')],
        ];

        if ($election->votableType === ElectionParty::class) {
            $rules['prefer_votes'] = ['nullable', 'array'];
            $rules['prefer_votes.*'] = ['nullable', 'integer', Rule::exists(Candidate::class, 'id')];
        }

        return $rules;
    }
}
