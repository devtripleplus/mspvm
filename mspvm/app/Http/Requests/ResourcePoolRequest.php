<?php

namespace App\Http\Requests;

class ResourcePoolRequest extends Request
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
            'disk' => 'required|numeric|min:0',
            'ram' => 'required|numeric|min:0',
            'swap' => 'required|numeric|min:0',
            'ips' => 'required|numeric'
        ];
    }
}
