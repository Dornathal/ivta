Feature: Showing Flight lists on various pages

  In order to inform about latest flights
  As a user
  I want to see flight lists at various pages on the website

  Background:
    Given I have an aircraft_model "B737-800" from "Boing"
    And I am logged in as PILOT
    And I am subscribed to airline BER
    And pilot PILOT owns aircraft_model "B737-800" with callsign "BER123" at airport "EGLL"
    And PILOT has a flight "BER2345" from "EDDF" to "EGLL" with aircraft "BER123" and status "FINISHED"
    And PILOT has a flight "BER5643" from "EGLL" to "EDDM" with aircraft "BER123" and status "EN_ROUTE"

  Scenario: Show latest flights on pilots profile
    Given I am on my profile

    Then I should see "EDDF - EGLL"
    And I should see "EGLL - EDDM"

  Scenario: Show latest flights on airlines profile
    Given I am on the "BER" airlines site

    Then I should see "EDDF - EGLL"
    And I should see "EGLL - EDDM"