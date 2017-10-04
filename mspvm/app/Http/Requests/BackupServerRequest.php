<?php

namespace App\Http\Requests;

class BackupServerRequest extends Request
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
            'host' => 'required',
            'user' => 'required',
            'password' => 'required',
            'port' => 'required|numeric',
            'directory' => 'required'
        ];
    }
}
