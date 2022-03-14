<?php

namespace App\Http\Requests\Lead;

use App\Rules\CheckUniqueEmail;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends CoreRequest
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
        if($this->type_id=='1'){
            $rules = [
                'client_name' => 'required',
                'email' => ['required','email',new CheckUniqueEmail($this->company_id, $this->route('lead'))],
               // 'company_name'=>'required',
                'type_id'=>'required',
            ];
    }else{
        $rules = [
            'client_name' => 'required',
            'email' => ['required','email',new CheckUniqueEmail($this->company_id, $this->route('lead'))],
            'company_name'=>'required',
            'type_id'=>'required',
        ];
    }

        return $rules;
    }

}
