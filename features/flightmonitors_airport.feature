Feature: Arriving and departing flights tracked by IVTA should be visible in airports' flight monitors

  In order to inform the user
  As a User
  I want to see all airport details


  Background: When the aircraft is loading it should appear in departing List of Airport page
    Given I have an aircraft_model "B737-800" from "Boing"
    And airline "BER" owns aircraft_model "B737-800" with callsign "BER451" at airport "EDDF"
    And user is logged in as PILOT
    And I have "EDDF|EGLL" not generating freight

  Scenario Outline: an Active Flight should be seen on the Airports' sites
    Given I have a flight "AB4578" from "EDDF" to "EGLL" with aircraft "BER451" and status "<FlightStatus>"
    And I am on the "EGLL" airports site

    Then I should not see "BER451" in "Departure"
    And I should see "BER451" in "Arrival"

    When I follow "EDDF"
    Then I should see "BER451" in "Departure"
    And I should not see "BER451" in "Arrival"

    Examples:
      | FlightStatus |
      | PLANNING     |
      | LOADING      |
      | UNLOADING    |

  Scenario Outline: an InActive Flight should not be seen on eithers
    Given I have a flight "AB4578" from "EDDF" to "EGLL" with aircraft "BER451" and status "<FlightStatus>"
    And I am on the "EDDF" airports site

    Then I should not see "BER451" in "Departure"
    And I should not see "BER451" in "Arrival"

    And I am on the "EGLL" airports site

    Then I should not see "BER451" in "Departure"
    And I should not see "BER451" in "Arrival"

  Examples:
  | FlightStatus |
  | EN_ROUTE     |
  | FINISHED     |
  | ABORTED      |

  Scenario:An En-Route Flight should be seen in boths en_route tables
    Given I have a flight "AB4578" from "EDDF" to "EGLL" with aircraft "BER451" and status "EN_ROUTE"
    And I am on the "EDDF" airports site
    Then I should see "BER451"

    Given I am on the "EGLL" airports site
    Then I should see "BER451"