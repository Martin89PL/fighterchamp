Feature: Signup
  User (Fighter or Coach) can sign up for a tournament

  Background:
    Given there is a tournament "Fight Tournament 1"
    And I am logged in as fighter
    And I am on "/turnieje"
    And I follow "Fight Tournament 1"
    And I follow "Zapisy"

    @javascript
  Scenario: Signup for a tournament
      And I select "Boks" from "Dyscyplina"
      And I select "A" from "Klasa"
      And I select "100" from "Kategoria wagowa"
      And I press "Wyślij moje zgłoszenie"
      Then I should see "Liczba zapisanych: 1 / 10"