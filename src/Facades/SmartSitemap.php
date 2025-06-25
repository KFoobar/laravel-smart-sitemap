<?php

namespace KFoobar\SmartSitemap\Facades;

use Illuminate\Support\Facades\Facade;
use KFoobar\SmartSitemap\Factories\SmartSitemapFactory;

class SmartSitemap extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SmartSitemapFactory::class;
    }
}
