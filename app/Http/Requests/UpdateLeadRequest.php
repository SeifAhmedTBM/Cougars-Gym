<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLeadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lead_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'phone' => [
                'nullable',
                'required',
                'min:10',
                'max:10'
            ],
            'national' => [
                'nullable',
                'min:10',
                'max:10'
            ],
            'status_id' => [
                'required',
                'integer',
            ],
            'source_id' => [
                'required',
                'integer',
            ],
            'gender' => [
                'required',
            ],
            'sales_by_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
