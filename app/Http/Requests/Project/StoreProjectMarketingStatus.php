<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectMarketingStatus extends CoreRequest
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
            'marketing_status' => 'required|unique:project_marketing_status,marketing_status,null,null,company_id,' . company_setting()->id,

        ];
    }

}
