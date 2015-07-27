Feature:

  Background:
    Given airport "EDDF" has size "INTERNATIONAL"

    @javascript
  Scenario: I should be able to deliver an aircraft to an Interkontinental Airport
    And I have an aircraft_model "B737-800" from "Boing"
    And pilot PILOT owns an "B737-800" with callsign "BER4567"
    And I am logged in as PILOT
    And I am on my profile

    When I follow "Deliver"
    And I select "EDDF" from "Icao"
    And I press "Deliver"

    Then aircraft "BER4567" should be located at "EDDF"