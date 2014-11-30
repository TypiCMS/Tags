<?php
namespace TypiCMS\Modules\Tags\Composers;

use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->menus['content']->put('tags', [
            'weight' => Config::get('tags::admin.weight'),
            'request' => $view->prefix . '/tags*',
            'route' => 'admin.tags.index',
            'icon-class' => 'icon fa fa-fw fa-tags',
            'title' => 'Tags',
        ]);
    }
}
