<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'activities.*.project_id' => 'required|exists:projects,id',
            'activities.*.user_id' => 'required|exists:users,id',
            'activities.*.task_category_id' => 'required|exists:task_categories,id',
            'activities.*.date' => 'required|date_format:Y-m-d',
            'activities.*.duration' => 'required|integer|min:60',
        ];
    }
}
