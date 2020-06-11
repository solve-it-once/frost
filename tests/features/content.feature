Feature: Content
  In order to test some basic Behat functionality
  As a website user
  I need to be able to see that the Drupal drivers are working

@api
Scenario: View the homepage headers
  Given I am not logged in
  And I am on "/"
  Then I should see the text "Masterfully Modular"
