<?php

namespace App\Observers;

use App\ProjectBusiness;

class ProjectBusinessObserver
{

    public function saving(ProjectBusiness $notes)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

}
