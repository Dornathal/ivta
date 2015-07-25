Feature:


  Background:
    Given I am logged in as PILOT

  Scenario Outline: Transportable Fright is shown in the Destinations Airport Dashboard
    Given there are <amount> <freight_type> at "EDDF" from "EDDF" to "EGLL"
    And I am on the "EDDF" airports site

    Then I should see <amount> <freight_type> from "EDDF"
    And I should see <amount> <freight_type> to "EGLL"

    Examples:
      | freight_type | amount |
      | Packages     | 12     |
      | Post         | 450    |
      | Economy      | 250    |
      | Business     | 45     |
      | First Class  | 12     |

  Scenario: Transportable Freight is added when same destinations
    Given there are 12 Packages at "EDDF" from "EDDF" to "EGLL"
    And there are 75 Packages at "EDDF" from "EDDF" to "EDDK"
    And there are 75 Packages at "EDDF" from "EDDK" to "EDDK"
    And I am on the "EDDF" airports site

    Then I should see 87 Packages from "EDDF"
    And I should see 75 Packages from "EDDK"
    And I should see 12 Packages to "EGLL"
    And I should see 150 Packages to "EDDK"

  Scenario: Transportable Freight is added to all possible Destinations
    Given I have "EDDF" not generating freight
    And there are 12 Packages at "EDDL" from "EDDL" to "EDDM"

    And I am on the "EDDL" airports site

    Then I should see 12 Packages to "EDDF"
    And I should see 12 Packages to "EDDM"
