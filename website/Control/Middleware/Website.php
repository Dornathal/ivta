<?php

namespace Control\Middleware;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 11.07.15
 * Time: 23:26
 */
class Website extends \Slim\Middleware
{

    public function call()
    {
        $app = $this->app;

        $website = array('title' => 'IVAO Transport Airline', 'author' => 'Fabian Haase');
        $app->view->appendData(array('website' => $website));

        $this->next->call();
    }
}