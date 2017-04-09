Feature: Test Api
  Scenario: Start
    Given A void database
    When I send a GET request to "/"
    Then the response code should be 200
