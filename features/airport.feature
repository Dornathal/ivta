Feature: Airports

  In order To display all kinds of Airports
  As a admin
  I want to be able to edit and create all Airport data

  Scenario: See an already existing airport in the airlines List
    Given I am on the "EDDF" airports site
    And I am on the airports site

    Then I should see "Frankfurt"

  Scenario: See an already existing airport in a detailed view
    Given I am on the "EDDK" airports site

    Then I should see "Koln Bonn"