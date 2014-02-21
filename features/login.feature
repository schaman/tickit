Feature: User Login
    In order to access the application
    As a registered user
    I need to be able to login

    Background:
         Given the following users exist:
             | username | email           | password  | role       |
             | root     | hello@tickit.io | mypass123 | ROLE_ADMIN |

    Scenario: The login form is visible
        Given I am currently on "/login"
         Then I should see a "form" element

    Scenario: User cannot log in with bad credentials
        Given I am currently on "/login"
         When I fill in the following:
            | email-name | james.t.halsall@googlemail.com |
            | login-pass | wrongpassword                  |
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"

    Scenario: User cannot login with non-existent credentials
        Given I am currently on "/login"
         When I fill in the following:
            | email-name | nonexistentusername  |
            | login-pass | dkwoafwkaodkawodkwao |
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"

    Scenario: User cannot login with empty credentials
        Given I am currently on "/login"
          And I press "Login"
         Then I should be on "/login"
          And I should wait and see "Bad credentials"

    Scenario: User can login with valid email and password
        Given I am currently on "/login"
         When I fill in the following:
            | email-name | hello@tickit.io  |
            | login-pass | mypass123        |
          And I press "Login"
         Then I should wait and see a "div.account" element
          And I should be logged in as "hello@tickit.io"
          And I should be on "/dashboard"

    Scenario: User can login with valid username and password
        Given I am currently on "/login"
         When I fill in the following:
            | email-name | root       |
            | login-pass | mypass123  |
          And I press "Login"
         Then I should wait and see a "div.account" element
          And I should be logged in as "root"
          And I should be on "/dashboard"
