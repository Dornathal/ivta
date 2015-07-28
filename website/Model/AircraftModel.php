<?php

namespace Model;

use Model\Base\AircraftModel as BaseAircraftModel;

/**
 * Skeleton subclass for representing a row from the 'aircraft_types' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AircraftModel extends BaseAircraftModel
{
    const PackageWeight = 100;

    public function queryAircraftModelView($page){
        return array('aircraft' => $this->toArray(),
            'flights' => FlightQuery::queryFlights($page, FlightQuery::create()->filterByAircraftModel($this))
        );
    }

    /**
     * Set the value of [seats] column.
     *
     * @param int $seats new value
     * @return $this|\Model\AircraftModel The current object (for fluent API support)
     */
    public function setSeats($seats)
    {
        parent::setSeats($seats);

        $seat_verteilung = array(array(1,0,0), array(0.85, 0.15, 0), array(0.7, 0.20, 0.10));
        $verteilung = $seat_verteilung[$this->getClasses() - 1];

        $this->setPassengerLow($seats * $verteilung[0]);
        $this->setPassengerMid($seats * $verteilung[1]);
        $this->setPassengerHigh($seats * $verteilung[2]);

        return $this;
    }

    /**
     * Set the value of [weight] column.
     *
     * @param int $weight new value
     * @return $this|\Model\AircraftModel The current object (for fluent API support)
     */
    public function setWeight($weight)
    {
        parent::setWeight($weight);
        if($weight > 100)
            $this->setPackages(round($weight/100));
        else
            $this->setPost($weight);
        return $this;
    }


}
