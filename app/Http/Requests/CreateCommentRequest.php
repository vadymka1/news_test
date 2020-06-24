<?php

namespace App\Http\Requests;


class CreateCommentRequest extends ApiFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string',
            'news_id' => 'required|exists:news,id'
        ];
    }
}
