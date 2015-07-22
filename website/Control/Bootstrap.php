<?php

namespace Control;

use Control\Middleware\Authentication;
use Slim\Slim;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 11.07.15
 * Time: 23:34
 */

class Bootstrap
{

    /**
     * Bootstrip constructor.
     * @param Slim $app
     * @param Container $container
     */
    public function __construct(Slim $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
    }

    public function bootstrap(){
        $app = $this->app;
        $container = $this->container;

        $app->add($container['language']);
        $app->add($container['navigation']);
        $app->add($container['website']);

        $app->add(new Authentication());
        return $app;
    }

}