<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateConnectionRequestRequest extends FormRequest
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
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required_without:company_id',
            'company_id' => 'required_without:receiver_id|exists:companies,id',
            'status' => 'required|in:pending,accepted,declined',
        ];
    }

    public function prepareForValidation()
    {
        Log::debug("PREPARE FOR VALIDATION");
        Log::debug($this->all());
        $this->merge([
            'sender_id' => $this->user()->id,
        ]);
    }
}
