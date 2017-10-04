<?php

namespace App\Http\Requests;

class PackageRequest extends Request
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
            'ram' => 'required|numeric',
            'swap' => 'required|numeric',
            'disk' => 'required|numeric',
            'cpu_units' => 'required|numeric',
            'cpu_limit' => 'required|numeric',
            'bandwith_limit' => 'required|numeric',
            'inode_limit' => 'required|numeric',
            'burst' => 'required|numeric',
            'cpus' => 'required|numeric',
            'network_speed' => 'required|numeric'
        ];
    }
}
