Feature: User Login
    In order to login to the application
    As a registered user
    I need to be able to login

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
          And I should see "Bad credentials"
