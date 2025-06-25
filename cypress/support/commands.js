// cypress/support/commands.js

Cypress.Commands.add('registerUser', (username, email, password) => {
  cy.visit('http://localhost:8000/register');
  console.log('Inside registerUser:', { username, email, password });
  cy.get('[placeholder="Enter your name"]').type(username);
  cy.get('[placeholder="Enter your email"]').type(email);
  cy.get('[placeholder="Enter your password"]').type(password);
  cy.get('[placeholder="Confirm your password"]').type(password);
  cy.get('.el-button').click();

  cy.get('.el-notification__title', { timeout: 5000 }).should('contain', 'Success');
});
