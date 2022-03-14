<?php

namespace App;

use App\Observers\ProjectGeneralAssetObserver;
use App\Scopes\CompanyScope;

class ProjectGeneralAsset extends BaseModel
{
    protected $table = 'project_general_assets';
    protected $dates = ['lease_expiration'];

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectGeneralAssetObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    
}
