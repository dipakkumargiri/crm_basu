<?php

namespace App\Observers;

use App\ProjectGeneralAsset;

class ProjectGeneralAssetObserver
{

    public function saving(ProjectGeneralAsset $notes)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

}
