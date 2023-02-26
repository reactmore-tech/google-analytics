<?php

namespace ReactMoreTech\Analytics\Config;

use ReactMoreTech\Analytics\Analytics;
use Config\Services as BaseService;

class Services extends BaseService
{
    /**
     * The base auth class
     */
    public static function GoogleAnalytics(bool $getShared = true): Analytics
    {
        if ($getShared) {
            return self::getSharedInstance('GoogleAnalytics');
        }

        helper('collections');

        $config = new GA();
        return new Analytics($config);
    }
}