<?php
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Model\Aircraft;
use Model\AircraftQuery;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 14:07
 */

class AircraftSteps extends RawMinkContext implements \Behat\Behat\Context\Context
{

    private $constantContainer;

    /**
     * @internal param ConstantContainer $constantContainer
     */
    public function __construct()
    {
        $this->constantContainer = new ConstantContainer();
    }

    /**
     * @Transform :aircraft
     */
    public function castCallsignToAircraft($aircraft)
    {
        return AircraftQuery::create()
            ->findOneByCallsign($aircraft);
    }

    /**
     * @Given I am on the :callsign aircraft site
     */
    public function iAmOnTheAircraftSite($callsign)
    {
        $this->visitPath('/aircraft/' . $callsign);
    }

    /**
     * @Given aircraft :aircraft can transport
     * @param TableNode $table
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function pushAircraftCapacities(Aircraft $aircraft, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $freight_type = $this->constantContainer->FREIGHT_TYPES[$row['Freight_Type']];
            $aircraft->setByName($freight_type, $row['Amount']);
        }
        $aircraft->save();
    }

    /**
     * @Given aircraft :aircraft should be located at :icao
     */
    public function aircraftShouldBeLocatedAt(Aircraft $aircraft, $icao)
    {
        expect($aircraft->getAirport()->getICAO())->to->equal($icao);
    }

    /**
     * @Given aircraft :aircraft has status :status
     */
    public function aircraftHasStatus(Aircraft $aircraft, $status)
    {
        $aircraft->setStatus($status);
        $aircraft->save();
    }

    /**
     * @Then aircraft :aircraft should have status :status
     */
    public function aircraftShouldHaveStatus(Aircraft $aircraft, $status)
    {
        expect($aircraft->getStatus())->to->equal($status);
    }

    /**
     * @Then there should be aircraft :aircraft
     */
    public function thereShouldBeAircraft(Aircraft $aircraft)
    {
        expect($aircraft)->to->not->be->null;
    }

}