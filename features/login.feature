Feature: User Login
    In order to login to the application
    As a registered user
    I need to be able to login

    Scenario: The login form is visible
        Given I am on "/login"
         Then I should see a "form" element
