<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchStoreActivityRequest extends FormRequest
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
            'date' => 'required|date_format:Y-m-d',
            'logGroups.activities.*.project_id' => 'required|exists:projects,id',
            'logGroups.activities.*.user_id' => 'required|exists:users,id',
            'logGroups.activities.*.task_category_id' => 'required|exists:task_categories,id',
            'logGroups.activities.*.date' => 'required|date_format:Y-m-d',
            'logGroups.activities.*.duration' => 'required|integer|min:60',
        ];
    }
}
