<?php

namespace App;

use App\Observers\ProjectDetailObserver;
use App\Scopes\CompanyScope;

class ProjectDetail extends BaseModel
{
    protected $table = 'project_details';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectDetailObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    
}
