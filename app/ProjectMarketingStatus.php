<?php

namespace App;

use App\Observers\ProjectMarketingStatusObserver;
use App\Scopes\CompanyScope;

class ProjectMarketingStatus extends BaseModel
{
    protected $table = 'project_marketing_status';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectMarketingStatusObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

}
