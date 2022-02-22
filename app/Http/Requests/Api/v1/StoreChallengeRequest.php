<?php

namespace App\Http\Requests\Api\v1;

use App\Constants\ChallengeDifficulties;
use Illuminate\Foundation\Http\FormRequest;

class StoreChallengeRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|unique:challenges',
            'description' => 'required|min:10',
            'time_out' => 'required',
            'difficulty' => 'required|min:3',
            'func_template' => 'required|min:10',
            'test_template' => 'required|min:10',
        ];
    }
}
