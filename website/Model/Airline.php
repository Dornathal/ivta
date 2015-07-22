<?php

namespace Model;

use Model\Base\Airline as BaseAirline;

/**
 * Skeleton subclass for representing a row from the 'airlines' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Airline extends BaseAirline
{
    public function queryAirplanes(){
        return AircraftQuery::create()
            ->filterByAirline($this)
            ->joinAirport()
            ->joinAircraftType()
            ->select(array('Callsign','FlownDistance','NumberFlights','FlownTime','Status'))
            ->withColumn('Airport.icao', 'Location')
            ->withColumn('AircraftType.model', 'Model')
            ->find()->toArray();
    }

    public function buyAircraft(AircraftType $aircraft_model, $aircraft_number){
        $new_saldo = $this->getSaldo() - $aircraft_model->getValue();
        if($new_saldo < 0) return;

        $this->setSaldo($new_saldo);
        $this->save();

        $aircraft = new Aircraft();
        $aircraft->setAirline($this);
        $aircraft->setAircraftType($aircraft_model);
        $aircraft->setCallsign($this->callsign . $aircraft_number);
        $aircraft->save();
    }
}
