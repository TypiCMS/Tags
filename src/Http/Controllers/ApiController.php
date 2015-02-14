<?php
namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Tags\Repositories\TagInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
