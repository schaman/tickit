Feature: Project Browsing
    In order to find projects
    As a registered user
    I need to be able to browse and filter projects

    Background:
        Given I am a logged in user
          And the following clients exist:
            | name      |
            | Apple     |
            | Microsoft |
            | Samsung   |
          And client "Apple" has the following projects:
            | name          | status | client     |
            | Web Project 1 | active | Apple      |
            | Web Project 2 | active | Apple      |
          And client "Microsoft" has the following projects:
            | name          | status | client     |
            | Web Project 3 | active | Microsoft  |
            | Web Project 4 | active | Microsoft  |
          And client "Samsung" has the following projects:
            | name          | status | client     |
            | Web Project 5 | active | Samsung    |

    Scenario: The project listing is visible
        Given I am currently on "/projects"
         Then I should see 5 table rows

    Scenario: The project listing can be filtered by name
        Given I am currently on "/projects"
         When I fill in the following
            | tickit_project_filters[name] | Web Project 1 |
         Then I should wait and see 1 table rows

    Scenario: The project listing can be filtered by client
        Given I am currently on "/projects"
         When I select "Apple" from "tickit_project_filters[client]"
         Then I should wait and see 2 table rows

        # TODO
