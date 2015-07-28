<?php

namespace Model;

use Model\Base\AircraftModelQuery as BaseAircraftTypeQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'aircraft_types' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AircraftModelQuery extends BaseAircraftTypeQuery
{
    public static function getAircraftModels($page){
        return AircraftModelQuery::create()
            ->orderByModel()
            ->paginate($page, 10);
    }
}
