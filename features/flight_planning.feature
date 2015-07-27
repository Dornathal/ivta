Feature: Flight status is updated accordingly

  In order to start a flight
  As a pilot
  I want to be able to control my flights

  Background:
      Given I am logged in as PILOT
      And I am subscribed to airline BER
      And I have an aircraft_model "B737-800" from "Boing"
      And pilot PILOT owns aircraft_model "B737-800" with callsign "BER451" at airport "EDDF"
      And aircraft_model can transport
        | Freight_Type | Amount    |
        | Packages     | 1000      |
        | Economy      | 200       |

  Scenario: User can plan a Flight from an Airport
      Given there are 1750 Packages at "EDDF" from "EDDF" to "EDDL"
      And there are 150 Economy at "EDDF" from "EDDK" to "EDDL"
      And there is 70 Post at "EDDF" from "EDDF" to "EDDK"

      And I am on the "BER451" aircraft site

      Then I should see "Plan to EDDL"
      And I should see "Plan to EDDK"

      When I follow "Plan to EDDL"

      Then last flight should have status "PLANNING"
      And I should see "1000"
      And I should see "150"
      And I should not see "70"

    @javascript
  Scenario Outline: Flight changes status to represent current flight state
      Given PILOT has a flight "BER045" from "EDDF" to "EDDL" with aircraft "BER451" and status "<CurrentStatus>"
      And I am on the latest flights page

      When I follow "<Button>"
      And I wait 1 second

      Then last flight should have status "<NextStatus>"
      Examples:
       | Position |CurrentStatus| Button          | NextStatus |
       | EDDF     | PLANNING    | Start loading   | LOADING    |
       | EDDF     | LOADING     | Start flight    | EN_ROUTE   |
       | EDDL     | EN_ROUTE    | Start unloading | UNLOADING  |
       | EDDL     | UNLOADING   | Finish          | FINISHED   |

  Scenario: Freight should be unloaded if the flights is beeing finished
      Given PILOT has a flight "BER45" from "EDDF" to "EDDL" with aircraft "BER451" and status "UNLOADING"
      And flight "BER45" has 175 Economy from "EDDF" to "EGLL"
      And flight "BER45" has 45 Business from "EDDF" to "EDDL"
      And I am on the latest flights page

      When I follow "Finish"
      And I wait 1 second

      Then last flight should have status "FINISHED"
      And there should be 175 Economy at "EDDL" to "EGLL"
      And aircraft "BER451" should be located at "EDDL"

  Scenario: If user is not logged in, he should not be able to start a flight
    Given there is 1750 Packages at "EDDF" from "EDDF" to "EDDL"
    And I am on the "BER451" aircraft site
    When I follow "Logout"

    Then I should not see "Plan to"

  Scenario Outline: Status of aircraft should be updated according to current Flight status
    Given PILOT has a flight "BER0045" from "EDDF" to "EDDL" with aircraft "BER451" and status "<CurrentFlightStatus>"
    And I am on the latest flights page

    When I follow "<link>"

    Then aircraft "BER451" should have status "<NewAircraftStatus>"

    Examples:
      | CurrentFlightStatus | link              | NewAircraftStatus |
      | PLANNING            | Start loading     | LOADING           |
      | LOADING             | Start flight      | EN_ROUTE          |
      | EN_ROUTE            | Start unloading   | UNLOADING         |
      | UNLOADING           | Finish            | IDLE              |


  Scenario Outline: If aircraft status is not IDLE it should not be possible to plan a flight
    Given aircraft "BER451" has status "<Status>"
    And there is 1750 Packages at "EDDF" from "EDDF" to "EDDL"
    And I am on the "BER451" aircraft site

    Then I should not see "Plan to"
      Examples:
        | Status   |
        | LOADING  |
        | EN_ROUTE |
        | UNLOADING|

  Scenario: If freight is at destination don't show it in the List
    Given there is 1750 Packages at "EDDF" from "EDDF" to "EDDL"
    Given there is 250 Packages at "EDDF" from "EDDL" to "EDDF"
    And I am on the "BER451" aircraft site

    Then I should not see "250"
    And I should see "1750"
