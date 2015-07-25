<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Model\AircraftType;
use Model\AircraftTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class AircraftModelSteps extends RawMinkContext implements Context
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
     * @Transform :model
     */
    public function castModelToAircraftType($model)
    {
        return AircraftTypeQuery::create()
            ->findOneByModel($model);
    }

    /**
     * @Given I have an aircraft_model :model from :brand
     */
    public function newAircraftModel($model, $brand)
    {
        $aircraft_model = new AircraftType();
        $aircraft_model->setBrand($brand);
        $aircraft_model->setModel($model);
        $aircraft_model->setWeight(0);
        $aircraft_model->setValue(0);
        $aircraft_model->save();
    }

    /**
     * @When I search for aircraft_model :aircraft_model
     */
    public function visitAircraftModel($model)
    {
        $this->visitPath('/aircraft/models/' . $model);
    }

    /**
     * @When I search for aircraft_models
     */
    public function visitAircraftModels()
    {
        $this->visitPath('/aircraft/models');
    }

    /**
     * @Given aircraft_model can transport
     * @param TableNode $table
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function pushAircraftModelCapacities(TableNode $table)
    {
        $aircraft_model = $this->lastCreatedAircraftModel();
        foreach ($table->getHash() as $row) {
            $freight_type = $this->constantContainer->FREIGHT_TYPES[$row['Freight_Type']];
            $aircraft_model->setByName($freight_type, $row['Amount']);
        }
        $aircraft_model->save();

    }

    /**
     * @Given aircraft_model has a value of :value
     */
    public function setAircraftModelValue($value)
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
        return AircraftTypeQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
    }

}