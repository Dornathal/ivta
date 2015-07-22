<?php

namespace Model;

use Model\Base\Pilot as BasePilot;
use Slim\Slim;

/**
 * Skeleton subclass for representing a row from the 'pilots' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Pilot extends BasePilot
{

    public static function login(Slim $app)
    {
        $ivao_token = $app->getCookie('IVAOTOKEN');
        $user=null;
        if($ivao_token != null) {
            $user = PilotQuery::create()->findOneByToken($ivao_token);
            if($user == null)
                self::createPilot($ivao_token);
        }else {
            $user = new Pilot();
            $user->setRank("GUEST");
            $user->setName("GUEST");
        }
        $app->view()->appendData(array('user' => $user->toArray()));
        return $user;
    }

    public static function register($name, $token)
    {
        $pilot = new Pilot();
        $pilot->setName($name);
        $pilot->setToken($token);
        $pilot->save();
        return $pilot;
    }

    public static function createPilot($token)
    {
        return self::register('TODO', $token);
    }
}
