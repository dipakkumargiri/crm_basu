<?php

namespace App;

use App\Observers\ProjectAggrementObserver;
use App\Scopes\CompanyScope;

class ProjectAggrement extends BaseModel
{
    protected $table = 'project_aggrements';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectAggrementObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /*public static function byProject($id)
    {
        return ProjectMember::join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $id)
            ->where('users.status', 'active')
            ->get();
    }*/

    
}
