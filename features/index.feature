Feature: Test Api
  Scenario: Start
    When I send a GET request to "/"
    Then the response code should be 200

  Scenario: Creating a Document
    When I send a POST request to "/documents" with values:
      | title       | La catedral del mar      |
      | Description | Descripcion de la novela |
    #And and the example pdf file
    Then the response code should be 200
    And the response should contain json:
    """
    {"created":"true"}
    """

  Scenario: Creating a Second Document
    When I send a POST request to "/documents" with values:
      | title       | Los pilares de la tierra  |
      | description | Descripcion del libro     |
    #And and the example pdf file
    Then the response code should be 200
    And the response should contain json:
    """
    {"created":"true"}
    """

  Scenario: Showing the list
    When I send a GET request to "/documents"
    Then the response code should be 200
    And the response should contain json:
    """
    {"documents":[{"id":"11","title":"Probe title","description":" probe description"}]}
    """
  Scenario: Deleting an existing document
    When I send a DELETE request to "/documents/2"
    Then the response code should be 200
    """
    {"deleted":"true"}
    """

  Scenario: Deleting a non-existing document
    When I send a DELETE request to "/documents/3"
    Then the response code should be 400
    And the response should contain json:
    """
    {"error":"Document not found"}
    """