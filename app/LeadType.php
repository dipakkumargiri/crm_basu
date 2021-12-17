<?php

namespace App;

use App\Observers\LeadTypeObserver;
use App\Scopes\CompanyScope;

class LeadType extends BaseModel
{
    protected $table = 'lead_type';
    protected $default = ['id', 'lead_name'];

    public static function boot()
    {
        parent::boot();
        static::observe(LeadTypeObserver::class);
        static::addGlobalScope(new CompanyScope);
    }

}
