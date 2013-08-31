Feature: User Login
    In order to access the application
    As a registered user
    I need to be able to login

    Scenario: The login form is visible
        Given I am currently on "/login"
         Then I should see a "form" element

    Scenario: User cannot log in with bad credentials
        Given I am currently on "/login"
         When I fill in the following:
            | Email or username | james.t.halsall@googlemail.com |
            | Password          | wrongpassword                  |
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
          And I should not be logged in
          And I should wait and see "Bad credentials"

    Scenario: User can login with valid email and password
        Given I am currently on "/login"
         When I fill in the following:
            | Email or username | james.t.halsall@googlemail.com  |
            | Password          | password                        |
          And I press "Login"
         Then I should wait and see a "#account" element
          And I should be logged in
          And I should be on "/dashboard"

    Scenario: User can login with valid email and password
        Given I am currently on "/login"
         When I fill in the following:
            | Email or username | james     |
            | Password          | password  |
          And I press "Login"
         Then I should wait and see a "#account" element
          And I should be logged in
          And I should be on "/dashboard"
