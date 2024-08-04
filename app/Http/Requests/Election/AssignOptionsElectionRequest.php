<?php

namespace App\Http\Requests\Election;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AssignOptionsElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('assignOptions', Election::class);
    }

    public function rules(): array
    {
        /** @var $election Election */
        $election = $this->route('election');

        if ($election->votableType === ElectionParty::class) {
            return [
                'options' => ['nullable', 'array'],
                'options.*' => ['nullable', 'string', Rule::exists(ElectionParty::class, 'id')],
                'subOptions' => ['nullable', 'array'],
                'subOptions.*' => ['nullable', 'string', Rule::exists(Candidate::class, 'id')],
            ];
        } elseif ($election->votableType === Candidate::class) {
            return [
                'options' => ['nullable', 'array'],
                'options.*' => ['nullable', 'string', Rule::exists(Candidate::class, 'id')],
            ];
        }

        return [];
    }
}
