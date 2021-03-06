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
            | name          | status |
            | Web Project 1 | active |
            | Web Project 2 | active |
          And client "Microsoft" has the following projects:
            | name          | status |
            | Web Project 3 | active |
            | Web Project 4 | active |
          And client "Samsung" has the following projects:
            | name             | status   |
            | Web Project 5    | active   |
            | Archived project | archived |

    Scenario: The project listing is visible
        Given I am currently on "/projects"
         Then I should wait and see 6 table rows

    Scenario: The project listing can be filtered by name
        Given I am currently on "/projects"
         When I fill in the following:
            | tickit_project_filters[name] | Web Project 1 |
         Then I should wait and see 1 table rows

    Scenario: The project listing can be filtered by client
        Given I am currently on "/projects"
         When I type "Appl", wait and select "Apple" from picker "tickit_project_filters_client"
         Then I should wait and see 2 table rows

    Scenario: The project listing can be filtered by status
        Given I am currently on "/projects"
         When I select "Archived" from "tickit_project_filters_status"
         Then I should wait and see 1 table rows

    Scenario: The project listing can be filtered by multiple fields
        Given I am currently on "/projects"
         When I fill in the following:
            | tickit_project_filters[name] | Web Project |
          And I type "Microso", wait and select "Microsoft" from picker "tickit_project_filters_client"
         Then I should wait and see 2 table rows

    Scenario: The project listing can be viewed on multiple pages
        Given There are 30 random projects for "Apple"
          And I am currently on "/projects"
          And I type "Appl", wait and select "Apple" from picker "tickit_project_filters_client"
         Then I should wait and see 25 table rows
          And I should see 2 pages
         When I click page 2
         Then I should wait and see 6 table rows

    Scenario: The project listing can be refreshed
        Given I am currently on "/projects"
          And I select "Archived" from "tickit_project_filters_status"
         Then I should wait and see 1 table rows
         Then When project "Archived project" is removed
          And I refresh the listing
         Then I should wait and see 0 table rows

    Scenario: The project listing should have an empty state
        Given I am currently on "/projects"
         When I fill in the following:
            | tickit_project_filters[name] | somethingthatwillreturnzeroresults |
         Then I should wait and see 0 table rows
          And I should see "No projects were found that match your criteria"