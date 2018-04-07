@user
Feature: Login to the api
  In order to login to the api we must have a registred user

  Scenario: Login to the api
    Given the "Accept" request header is "application/json"
    And the following form parameters are set:
      | name     | value           |
      | username | admin@admin.now |
      | password | admin           |

    Then I request "/api/login" using HTTP POST
    And the response body contains JSON:
    """
    {
      "token_type": "Bearer",
      "account_id": 1,
      "account_role": "ADMIN",
      "expires_in": 3600
    }
    """
    Then the response code is 200

  Scenario: Wrong login
    Given the "Accept" request header is "application/json"
    And the following form parameters are set:
      | name     | value           |
      | username | user@wrong.now  |
      | password | user            |

    Then I request "/api/login" using HTTP POST
    And the response body contains JSON:
    """
    {
      "errors": [
          {
              "status": "401",
              "error": "Unauthorized",
              "detail": "Wrong credentials."
          }
      ]
    }
    """
    Then the response code is 401