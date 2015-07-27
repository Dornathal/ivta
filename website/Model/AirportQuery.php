<?php

namespace Model;

use Model\Base\AirportQuery as BaseAirportQuery;
use Model\Map\AirportTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'airports' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AirportQuery extends BaseAirportQuery
{
    /**
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public static function queryDeliverableAirports(){
        return AirportQuery::create()
            ->filterBySize(array(AirportTableMap::COL_SIZE_INTERNATIONAL, AirportTableMap::COL_SIZE_INTERKONTINENTAL), Criteria::IN)
            ->select('Icao')
            ->find()->toArray();
    }
}
