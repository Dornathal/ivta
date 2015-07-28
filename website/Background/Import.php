<?php
use Model\Airport;
use Model\AirportQuery;
use Model\Flight;
use Propel\Runtime\Propel;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 15.07.15
 * Time: 11:46
 */

class Import
{
    const RESOURCE_DIR = 'resources';

    /**
     * Import constructor.
     */
    public function __construct($HOME='')
    {
        $this->RESORCE_PATH = $HOME . self::RESOURCE_DIR;
    }

    public function airports(){
        $file = $this->RESORCE_PATH . '/airports.dat';
        $csv = $this->readCSV($file);

        Flight::transaction(function () use ($csv){
            foreach($csv as $array){
                $airport = new Airport();
                $airport->setId($array[0]);
                $airport->setName($array[1]);
                $airport->setCity($array[2]);
                $airport->setCountry($array[3]);
                $airport->setIATA($array[4]);
                $airport->setICAO($array[5]);

                $airport->setCoordinates($array[6], $array[7]);
                $airport->setAltitude($array[8]);

                $airport->setTimezone($array[9]);
                $airport->setDst($array[10]);

                $airport->save();
            }
        });
    }

    public function airlines()
    {
        $file = $this->RESORCE_PATH . '/airlines.dat';
        $csv = $this->readCSV($file);

        Flight::transaction(function () use ($csv){
            foreach($csv as $array){
                $airline = new \Model\Airline();
                $airline->setId($array[0]);
                $airline->setName($array[1]);
                $airline->setAlias($array[2]);
                $airline->setIATA($array[3]);
                $airline->setICAO($array[4]);
                $airline->setCallsign($array[5]);

                $airline->setCountry($array[6]);
                $airline->setActive($array[7]);

                $airline->save();
            }
        });
    }

    public function aircraft_models(){
        $file = $this->RESORCE_PATH . '/aircraft_models.csv';
        $csv = $this->readCSV($file, 1);

        Flight::transaction(function () use ($csv){
            foreach($csv as $array){
                $airline = new \Model\AircraftModel();
                $airline->setId($array[0]);
                $airline->setBrand($array[1]);
                $airline->setModel($array[2]);
                $airline->setICAO($array[3]);

                $airline->setClasses($array[5]);
                $airline->setSeats($array[4]);
                $airline->setEngineType($array[6]);
                $airline->setEngineCount($array[7]);

                $airline->setFlightRange($array[8]);
                $airline->setCruisingSpeed($array[9]);

                $airline->setWeight($array[11]);
                $airline->setWTC($array[12]);

                $airline->save();
            }
        });
    }

    /**
     * @param $file
     * @return array
     */
    private function readCSV($file, $skip=0)
    {
        $csv = null;
        if (($handle = fopen($file, "r")) !== FALSE) {
            $csv = array();
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($skip-- > 0) continue;
                array_push($csv, $data);
            }
            fclose($handle);
        }
        return $csv;
    }

    public function database()
    {

    }
}
