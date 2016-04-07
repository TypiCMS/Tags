<?php

namespace TypiCMS\Modules\Tags\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.content'), function (SidebarGroup $group) {
            $group->addItem(trans('tags::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.tags.sidebar.icon', 'icon fa fa-fw fa-tags');
                $item->weight = config('typicms.tags.sidebar.weight');
                $item->route('admin::index-tags');
                $item->append('admin::create-tag');
                $item->authorize(
                    Gate::allows('index-tags')
                );
            });
        });
    }
}
