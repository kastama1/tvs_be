<?php

namespace App\Http\Requests\ElectionParty;

use App\Models\ElectionParty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreElectionPartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('store', ElectionParty::class);
    }

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
