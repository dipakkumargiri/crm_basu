<?php

namespace App\Observers;

use App\ProjectAggrement;

class ProjectAggrementObserver
{

    public function saving(ProjectAggrement $notes)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

}
