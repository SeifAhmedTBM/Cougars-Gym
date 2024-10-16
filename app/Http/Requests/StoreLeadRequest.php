<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLeadRequest extends FormRequest
{
    public function authorize()
    {
        // return Gate::allows('lead_create');
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'phone' => [
                'string',
                'required',
                'min:10',
                'max:10',
                'unique:leads,phone,NULL,id,deleted_at,NULL',
            ],
            // 'national' => [
            //     'nullable',
            //     'min:10',
            //     'max:10',
            //     'unique:leads,phone,NULL,id,deleted_at,NULL',
            // ],
            // 'status_id' => [
            //     'required',
            //     'integer',
            // ],
            // 'source_id' => [
            //     'required',
            //     'integer',
            // ],
            'gender' => [
                'required',
            ],
             'branch_id' => [
                 'required',
                 'integer',
                 'exists:branches,id',
             ],
        ];
    }
}
