Feature: index
  Scenario: not yet logged
    When I request "/"
    Then I receive "Hello world"
