@user
Feature: CRUD for user model

  Background:
    Given there are the following users in the database:
    | Name     | Email           | Password |
    | Admin    | admin@admin.now | abc!1234 |
    | John Doe | john@doe.com    | 1234abc! |

  Scenario: Create a user object
    Given the "Accept" request header is "application/json"
    And the request body is:
    """
    {
        "type": "users",
	    "data": {
		"attributes": {
			"name": "John Doe",
			"email": "john@doe.com",
			"password": "1234abc!"
		}
	}
    """
    Then I request "/api/users" using HTTP POST
    And the response body contains JSON:
    """
    {

    }
    """
    Then the response code is 201


  Scenario: Update a user
    Given the request body is:
    """
    {
      "data": {
    	"type": "users",
    	"id": "2",
    	"attributes": {
    		"password": "1234abc1aa2345-updated"
        }
      }
    }
    """
    Then I request "/api/users/2" using HTTP PATCH
    And the response body contains JSON:
    """
    """
    Then the response code is 200


  Scenario: Read a user
    Given I request "/api/users" using HTTP GET
    And the response body contains JSON:
    """
    """
    Then the response code is 200

    Given I request "/api/users/2" using HTTP GET
    And the response body contains JSON:
    """
    {
      "data": {
    	"type": "users",
    	"attributes": {
    		"email": "user@user.now",
    		"password": "1234abc1aa2345-updated",
    		"name": "User",
    		"role": "USER",
    		"created": "1234541235444",
    		"verified": true
        }
      }
    }
    """

    Then the response code is 200


  Scenario: Update a user
    Given I request "/api/users/2" using HTTP DELETE
    When the response code is 200
    Then I request "/api/users/2" using HTTP GET

