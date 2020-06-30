<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:120', 
            'description' => 'max:1200', 
            'duration' => 'integer|min:1|max:600', 
            // 'user_id' => 'required|exists:users,id', // this is the authenticated user via token
            'date' => 'nullable|date_format:Y-m-d' // if empty, it'll use today
        ];
    }
}
