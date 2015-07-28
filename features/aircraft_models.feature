Feature: See aircraft models on the website

  In order to inform me about various available aircraft_models
  As a user
  I want to be able to search for aircraft_models

  Scenario: When multiple Aircraft Models would fit the search query show a selection of all fitting

    When I search for aircraft_model "B737-900"

    Then I should see "B737-900ER"
    And I should see "B737-900"
    And I should not see "B737-800"

  Scenario: Show all aircraft_models on the page

    When I search for aircraft_models

    Then I should see "B737-100"
    And I should see "B737-200"
    And I should see "B737-300"