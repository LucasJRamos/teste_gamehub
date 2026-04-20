<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePortfolioItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::in(['image', 'link'])],
            'file' => ['required_if:type,image', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'link_url' => ['required_if:type,link', 'nullable', 'url', 'max:500'],
        ];
    }
}
