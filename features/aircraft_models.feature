Feature: See aircraft models on the website

  In order to inform me about various available aircraft_models
  As a user
  I want to be able to search for aircraft_models

  Background:
    Given I have an aircraft_model "B737-800" from "Boing"
    And I have an aircraft_model "B737-900" from "Boing"
    And I have an aircraft_model "A350-900" from "Airbus"

  Scenario: When multiple Aircraft Models would fit the search query show a selection of all fitting

    When I search for aircraft_model "B737"

    Then I should see "B737-800"
    And I should see "B737-900"
    And I should not see "A350-900"

  Scenario: Show all aircraft_models on the page

    When I search for aircraft_models

    Then I should see "B737-800"
    And I should see "B737-900"
    And I should see "A350-900"