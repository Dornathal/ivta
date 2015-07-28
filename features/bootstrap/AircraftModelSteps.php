<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Model\AircraftModel;
use Model\AircraftModelQuery;
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
        return AircraftModelQuery::create()
            ->findOneByModel($model);
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
     * @Given aircraft_model :model has a value of :value
     */
    public function setAircraftModelValue(AircraftModel $model, $value)
    {
        $model->setValue($value);
        $model->save();
    }

    /**
     * @return AircraftModel
     */
    private function lastCreatedAircraftModel()
    {
        return AircraftModelQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
    }

    /**
     * @Given aircraft_model has settings
     */
    public function aircraft_modelHasSettings(TableNode $table)
    {
        $aircraft_model = $this->lastCreatedAircraftModel();
        foreach ($table->getHash() as $row) {
            $aircraft_model->setByName($row['Column'], $row['Value']);
        }
        $aircraft_model->save();
    }

}