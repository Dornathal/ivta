Feature:

  Scenario: I want to be able to login and logout
    When IVAO sends callback "PILOT"
    And I follow "Logout (Behat Runner)"

    Then I should see "Login"



