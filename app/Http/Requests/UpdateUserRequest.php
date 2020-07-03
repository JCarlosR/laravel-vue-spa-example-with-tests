<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = User::$updateValidationRules;
        
        if ($this->user()->role === User::ROLE_ADMIN) {
            $rules += [
                'role' => Rule::in([
                    User::ROLE_ADMIN,
                    User::ROLE_USER_MANAGER,
                    User::ROLE_REGULAR_USER
                ])
            ];
        }
        
        return $rules;
    }
}
