<?php

namespace App\Http\Requests\Election;

use App\Enum\ElectionTypeEnum;
use App\Models\Election;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('create', Election::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(ElectionTypeEnum::class)],
            'info' => ['nullable', 'string'],
            'publish_from' => ['required', 'date', 'after:today'],
            'start_from' => ['required', 'date', 'after:publish_from'],
            'end_to' => ['required', 'date', 'after:start_from'],
        ];
    }
}
