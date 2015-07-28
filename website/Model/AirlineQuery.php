<?php

namespace Model;

use Model\Base\AirlineQuery as BaseAirlineQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'airlines' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AirlineQuery extends BaseAirlineQuery
{
    public static function getAirlines($page){
        return AirlineQuery::create()
            ->orderByICAO()
            ->joinAircraft()
            ->setDistinct()
            ->paginate($page, 10);
    }
}
