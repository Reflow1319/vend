<?php

namespace App\Http\Requests;

class TaskRequest extends CustomFormRequest
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
            'title' => 'required|max:255',
        ];
    }
}
