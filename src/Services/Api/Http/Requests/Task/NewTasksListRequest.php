<?php

namespace App\Services\Api\Http\Requests\Task;

use App\Services\Api\Http\Requests\FormRequest;

class NewTasksListRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'radius' => 'required|integer',
            'lat'    => 'required|numeric',
            'lng'    => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'radius' => _('Radius'),
            'lat'    => _('Latitude'),
            'lng'    => _('Longitude')
        ];
    }
}