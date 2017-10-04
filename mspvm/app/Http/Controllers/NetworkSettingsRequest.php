<?php

namespace App\Http\Requests\Settings;

Use App\Http\Requests\Request;

class NetworkSettingsRequest extends Request
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
            'networkadapter' => 'required'
        ];
    }
}
