<?php
use Behat\Behat\Context\BehatContext;
use Model\AircraftType;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 00:12
 */
class AircraftModelSteps extends BehatContext
{
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
}