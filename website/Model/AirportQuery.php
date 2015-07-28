<?php

namespace Model;

use Model\Base\AirportQuery as BaseAirportQuery;
use Model\Map\AirportTableMap;
use Propel\Runtime\ActiveQuery\Criteria;


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

    public static function getAirports($page){
        return AirportQuery::create()
            ->joinFreightGeneration()
            ->orderBy('FreightGeneration.Capacity',Criteria::ASC)
            ->paginate($page, 10);
    }
}
