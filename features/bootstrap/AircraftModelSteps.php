<?php
use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Model\AircraftType;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 00:12
 */
class AircraftModelSteps extends BehatContext
{
    /**
     * AircraftModelSteps constructor.
     * @param ConstantContainer $constantContainer
     */
    public function __construct(ConstantContainer $constantContainer)
    {
        $this->constantContainer = $constantContainer;
    }


    /**
     * @Given /^I have an aircraft_model "([^"]*)" from "([^"]*)"$/
     */
    public function iHaveAnAircraft_modelFrom($model, $brand)
    {
        $aircraft_model = new AircraftType();
        $aircraft_model->setBrand($brand);
        $aircraft_model->setModel($model);
        $aircraft_model->setWeight(0);
        $aircraft_model->setValue(0);
        $aircraft_model->save();
    }

    /**
     * @When /^I search for aircraft_model "([^"]*)"$/
     */
    public function iSearchForAircraftModel($model)
    {
        $this->getMainContext()->visit('/aircraft/models/' . $model);
    }

    /**
     * @When /^I search for aircraft_models$/
     */
    public function iSearchForAircraft_models()
    {
        $this->getMainContext()->visit('/aircraft/models');
    }

    /**
     * @Given /^aircraft_model can transport$/
     */
    public function aircraft_modelCanTransport(TableNode $table)
    {
        $aircraft_model = $this->lastCreatedAircraftModel();

        foreach ($table->getHash() as $row) {
            $aircraft_model->setByName($this->constantContainer->FREIGHT_TYPES[$row['Freight_Type']], $row['Amount']);
        }
        $aircraft_model->save();

    }

    /**
     * @Given /^aircraft_model has a value of (\d+)$/
     */
    public function aircraft_modelHasAValueOf($value)
    {
        $aircraft_model = $this->lastCreatedAircraftModel();
        $aircraft_model->setValue($value);
        $aircraft_model->save();
    }

    /**
     * @return AircraftType
     */
    private function lastCreatedAircraftModel()
    {
        return \Model\AircraftTypeQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
    }

}