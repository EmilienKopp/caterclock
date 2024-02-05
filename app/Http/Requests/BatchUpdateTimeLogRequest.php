<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchUpdateTimeLogRequest extends FormRequest
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
            "entries.*.id" => "required|exists:time_logs,id",
            "entries.*.in_time" => "required|date",
            "entries.*.out_time" => "required|date",
            "entries.*.project_id" => "required|exists:projects,id",
            "entries.*.user_id" => "required|exists:users,id",
        ];
    }
}
