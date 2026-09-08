<?php

namespace Aic\Faq\Classes;

use Cms\Classes\Page;
use Cms\Classes\Theme;

final class ComponentHelper
{
    /**
     * Get Pages by CMS Component
     * 
     * @param string $component
     * @param bool $allOnEmpty
     * 
     * @return array
     */
    static function getPagesByComponent(string $component, bool $allOnEmpty = true)
    {
        $theme = Theme::getActiveTheme();
        $pages = Page::listInTheme($theme, true);

        $cmsPages = [];

        foreach ($pages as $page) {
            if (!$page->hasComponent($component)) {
                continue;
            }
            $cmsPages[$page->baseFileName] = $page->title ?? $page->id;
        }

        if (count($cmsPages) < 1) {
            return $allOnEmpty ? self::allPages() : [];
        } else {
            return $cmsPages;
        }
    }

    /**
     * Return all CMS Pages
     * @return array
     */
    protected static function allPages()
    {
        return Page
            ::listInTheme( Theme::getActiveTheme(), true)
            ->mapWithKeys(function($page) {
                return [$page->baseFileName => $page->title];
            })
            ->toArray();
    }
}
