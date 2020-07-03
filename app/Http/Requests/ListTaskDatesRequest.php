<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTaskDatesRequest extends FormRequest
{

    private $validFilterTypes = [
        'sevenDays',
        'thisMonth',
        'custom'
    ];
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filterType' => [
                'required',
                Rule::in($this->validFilterTypes)
            ],
            'from' => 'required_if:filterType,custom',
            'to' => 'required_if:filterType,custom'
        ];
    }
}
