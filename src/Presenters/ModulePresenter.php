<?php

namespace TypiCMS\Modules\Tags\Presenters;

use TypiCMS\Modules\Core\Presenters\Presenter;

class ModulePresenter extends Presenter
{
    /**
     * Get title.
     */
    public function title(): string
    {
        return $this->entity->tag;
    }
}
