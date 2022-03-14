<?php

namespace App\Observers;

use App\ProjectDetail;

class ProjectDetailObserver
{

    public function saving(ProjectDetail $notes)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

}
