<?php
namespace TypiCMS\Modules\Tags\Composers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;
use TypiCMS\Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.content'), function (SidebarGroup $group) {
            $group->addItem(trans('tags::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.tags.sidebar.icon', 'icon fa fa-fw fa-tags');
                $item->weight = config('typicms.tags.sidebar.weight');
                $item->route('admin.tags.index');
                $item->append('admin.tags.create');
                $item->authorize(
                    $this->user->hasAccess('tags.index')
                );
            });
        });
    }
}
