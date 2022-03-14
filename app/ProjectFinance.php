<?php

namespace App;

use App\Observers\ProjectFinanceObserver;
use App\Scopes\CompanyScope;

class ProjectFinance extends BaseModel
{
    protected $table = 'project_finances';
    protected $dates = ['activated_date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectFinanceObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    
}
