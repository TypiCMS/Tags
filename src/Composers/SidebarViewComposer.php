<?php

namespace TypiCMS\Modules\Tags\Composers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        if (Gate::denies('read tags')) {
            return;
        }
        $view->sidebar->group(__('Content'), function (SidebarGroup $group) {
            $group->id = 'content';
            $group->weight = 30;
            $group->addItem(__('Tags'), function (SidebarItem $item) {
                $item->id = 'tags';
                $item->icon = config('typicms.tags.sidebar.icon');
                $item->weight = config('typicms.tags.sidebar.weight');
                $item->route('admin::index-tags');
                $item->append('admin::create-tag');
            });
        });
    }
}
