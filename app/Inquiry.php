<?php

namespace App;

//use App\Observers\LeadObserver;
//use App\Scopes\CompanyScope;
//use App\Traits\CustomFieldsTrait;
use Illuminate\Notifications\Notifiable;

class Inquiry extends BaseModel
{
    //use Notifiable;
    //use CustomFieldsTrait;

    protected $table = 'Inquiry';
    protected $fillable = [
        'clint_name',
        'email',
        'company_name',
        'website',
        'address	',
        'mobile',
        'Message'
    ];
    
    

}
