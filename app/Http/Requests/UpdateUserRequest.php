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
        // there is no need to check here, since the Controller is using the UserPolicy on all methods 
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('user')->id; // edited user
        
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$userId,
            'password' => 'min:6'
        ];
        
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
