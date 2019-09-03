<?php

namespace TypiCMS\Modules\Tags\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Projects\Models\Project;
use TypiCMS\Modules\Tags\Presenters\ModulePresenter;

class Tag extends Base
{
    use Historable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $guarded = ['id', 'exit'];

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'taggable');
    }
}
