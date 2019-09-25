Feature: Authentication
  In order to login

  Background:
    And I am on the homepage
    When I follow "Login"

  Scenario: Loggin in
    Given there is and admin user "user@fighterchamp.com" with password "mypassword"
    And I fill in "Email" with "user@fighterchamp.com"
    And I fill in "Hasło" with "mypassword"
    And I press "Login"
    Then I should see "Logout"

  @javascript
  Scenario: Register as Fighter
    When I follow "Aby się zarejestrować kliknij TUTAJ"
    And I wait for result
    And I close Symfony Dev Toolbar
    And I select "1" from "user-type"
    And I wait for result
    And I fill in "Email" with "user@fighterchamp.com"
    And I fill in "Hasło" with "mypassword"
    And I fill in "Powtórz Hasło" with "mypassword"
    And I select "Mężczyzna" from "Płeć"
    And I fill in "Imię" with "Sławomir"
    And I fill in "Nazwisko" with "Grochowski"
    And I fill in "Telefon" with "666 666 666 "
    And I check "fighter_terms"
    And I wait for result
    And I press "Zarejestruj się"
    And I wait for result
    Then I should see "Sukces! Twój profil został utworzony! Jesteś zalogowany!"


