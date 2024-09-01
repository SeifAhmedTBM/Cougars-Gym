<?php

namespace App\Http\Requests;

use App\Models\Status;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('status_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'max:191',
                'required',
                'unique:statuses',
            ],
            'color' => [
                'string',
                'max:191',
                'required',
            ],
            // 'default_next_followup_days' => [
            //     'string',
            //     'max:191',
            //     'required',
            // ],
            'need_followup' => [
                'required',
            ],
        ];
    }
}
