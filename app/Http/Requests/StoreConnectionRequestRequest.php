<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConnectionRequestRequest extends FormRequest
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
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required_without:company_id|integer|exists:users,id',
            'company_id' => 'required_without:receiver_id|integer|exists:companies,id',
            'position' => 'required|string|in:owner,employee,hired_freelance,admin',
            'code' => 'nullable|string|exists:companies,code',
        ];
    }
}
