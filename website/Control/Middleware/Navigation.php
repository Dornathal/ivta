<?php

namespace Control\Middleware;

use Slim\Middleware;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 11.07.15
 * Time: 22:55
 */
class Navigation extends Middleware
{

    public function call()
    {
        $app = $this->app;

        $home = array('caption' => "Home" , 'href' => WEBSITE_ROOT . '/');
        $airline = array('caption' => "Airlines" , 'href' => WEBSITE_ROOT .'/airlines');
        $airport = array('caption' => "Airports" , 'href' => WEBSITE_ROOT .'/airports');
        $aircrafts = array('caption' => "Aircraft Models" , 'href' => WEBSITE_ROOT .'/aircraft/models');

        $navigation = array($home, $airport, $airline, $aircrafts);

        foreach ($navigation as &$link) {
            if ($link['href'] == $this->app->request()->getPath()) {
                $link['class'] = 'active';
            } else {
                $link['class'] = '';
            }
        }

        $app->view->appendData(array('navigation' => $navigation));
        $this->next->call();
    }
}