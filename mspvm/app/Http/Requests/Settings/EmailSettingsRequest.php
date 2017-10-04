<?php

namespace App\Http\Requests\Settings;

Use App\Http\Requests\Request;

class EmailSettingsRequest extends Request
{
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
            'method' => 'required',
            'host' => 'required_if:method,smtp',
            'port' => 'required_if:method,smtp|numeric',
            'security' => 'required_if:method,smtp',
            'user' => 'required_if:method,smtp',
            'password' => 'required_if:method,smtp'
        ];
    }
}
