<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'content' => 'required|min:2',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [

                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [

        ];
    }
}
