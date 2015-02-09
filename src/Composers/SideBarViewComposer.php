<?php
namespace TypiCMS\Modules\Tags\Composers;

use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->menus['content']->put('tags', [
            'weight' => config('typicms.tags.sidebar.weight'),
            'request' => $view->prefix . '/tags*',
            'route' => 'admin.tags.index',
            'icon-class' => 'icon fa fa-fw fa-tags',
            'title' => 'Tags',
        ]);
    }
}
