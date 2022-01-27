<?php

namespace App;

use App\Scopes\CompanyScope;
use App\Observers\LeadObserver;

class LeadAssignHistory extends BaseModel
{
    protected $table = 'lead_assign_history';
    // protected $fillable = ['user_id', 'note_id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(LeadObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    /*public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_agent')->withoutGlobalScopes(['active']);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'assigned_by')->withoutGlobalScopes(['active']);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }*/

}
