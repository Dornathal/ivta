<?php

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 13.07.15
 * Time: 20:01
 */
class BackgroundWorker
{

    /**
     * BackgroundWorker constructor.
     */
    public function __construct()
    {
    }

    public function updateWhazzup(){

    }

    public function generateFright(){

    }

    public function generatePassenger(){
        $airports = \Model\AirportQuery::create()->orderByPassenger(\Propel\Runtime\ActiveQuery\Criteria::DESC)->setLimit(10);
    }
}