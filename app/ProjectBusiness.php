<?php

namespace App;

use App\Observers\ProjectBusinessObserver;
use App\Scopes\CompanyScope;

class ProjectBusiness extends BaseModel
{
    protected $table = 'project_businesses';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectBusinessObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    
}
