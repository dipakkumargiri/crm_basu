<?php

namespace App;

use App\Observers\LeadStageObserver;
use App\Scopes\CompanyScope;

class LeadStage extends BaseModel
{
    protected $table = 'lead_stages';
    protected $default = ['id', 'stage_name'];

    public static function boot()
    {
        parent::boot();
        static::observe(LeadStageObserver::class);
        static::addGlobalScope(new CompanyScope);
    }

}
