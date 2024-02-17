<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company_id' => ['required_without:user_id', 'nullable', 'exists:companies,id'],
            'user_id' => ['required_without:company_id', 'nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget_low' => ['required', 'numeric'],
            'budget_high' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required_without' => 'The company field is required when the user field is empty.',
            'user_id.required_without' => 'The user field is required when the company field is empty.',
        ];
    }

    public function prepareForValidation(): void
    {
        if(!$this->has('company_id') && !$this->has('user_id')) {
            $this->merge([
                'user_id' => auth()->user()->id,
            ]);
        }
    }
}
