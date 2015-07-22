Feature:

  Background:

  Scenario: When multiple Aircraft Models would fit the search query show a selection of all fitting

    Given I have an aircraft_model "B737-800" from "Boing"
    And I have an aircraft_model "B737-900" from "Boing"
    And I have an aircraft_model "A350-900" from "Airbus"

    When I search for aircraft_model "B737"

    Then I should not see "404"
    Then I should see "B737-800"
    And I should see "B737-900"
    And I should not see "A350-900"
