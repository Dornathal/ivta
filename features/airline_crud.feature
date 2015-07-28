Feature: See airlines on Website which have an aircraft attached

  Scenario: See an airline with an aircraft on the website
    Given pilot PILOT owns an "B737-800" with callsign "BER1234"
    And I am on the airlines site

    Then I should see "Air Berlin"

  Scenario: Airline with no aircrafts are hidden
    Given I am on the airlines site

    Then I should not see "Air Berlin"