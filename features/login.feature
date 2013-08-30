Feature: User Login
    In order to access the application
    As a registered user
    I need to be able to login

    Background:
        Given there are following users:
            | email                   | username | password |
            | alexsnow@googlemail.com | alexsnow | secret   |

    Scenario: The login form is visible
        Given I am currently on "/login"
         Then I should see a "form" element

    Scenario: User cannot log in with bad credentials
        Given I am currently on "/login"
         When I fill in the following:
            | Email or username | invalidaddress@mail.com |
            | Password          | kdowakodwakodwakodwako  |
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"

    Scenario: User cannot login with non-existent credentials
        Given I am currently on "/login"
         When I fill in the following:
            | Email or username | nonexistentusername  |
            | Password          | dkwoafwkaodkawodkwao |
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"

    Scenario: User cannot login with empty credentials
        Given I am currently on "/login"
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"
