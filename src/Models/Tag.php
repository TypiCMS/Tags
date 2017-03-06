<?php

namespace TypiCMS\Modules\Tags\Models;

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

    /**
     * The default route for back office.
     *
     * @var string
     */
    protected $route = 'tags';

    /**
     * Define a many-to-many polymorphic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function projects()
    {
        return $this->morphedByMany(Project::class, 'taggable');
    }
}
