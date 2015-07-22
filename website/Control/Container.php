<?php

namespace Control;
use Control\Middleware\Language;
use Control\Middleware\Navigation;
use Control\Middleware\Website;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 11.07.15
 * Time: 23:35
 */
class Container extends \Pimple\Container
{

    /**
     * Container constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->configureContainer();
    }

    public function configureContainer(){

        $this['language'] = function () {
            return new Language();
        };

        $this['navigation'] = function () {
            return new Navigation();
        };

        $this['website'] = function () {
            return new Website();
        };
    }
}