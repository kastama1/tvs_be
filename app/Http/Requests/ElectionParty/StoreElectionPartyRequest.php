<?php

namespace App\Http\Requests\ElectionParty;

use App\Enum\ElectionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreElectionPartyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'campaign' => ['nullable', 'string'],
        ];
    }
}
