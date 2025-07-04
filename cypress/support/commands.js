// cypress/support/commands.js
import 'cypress-file-upload';


Cypress.Commands.add('registerUser', (username, email, password) => {
  cy.visit('http://localhost:8000/register');
  cy.get('[placeholder="Enter your name"]').type(username);
  cy.get('[placeholder="Enter your email"]').type(email);
  cy.get('[placeholder="Enter your password"]').type(password);
  cy.get('[placeholder="Confirm your password"]').type(password);
  cy.get('.el-button').click();
  cy.wait(1000);
  cy.get('.el-notification__title', { timeout: 5000 }).should('contain', 'Success');
});

Cypress.Commands.add('getLatestEmailConfirmationLink', () => {
  return cy.request('http://localhost:8025/api/v2/messages').then((response) => {
    const messages = response.body.items;

    const confirmationEmail = messages.find((msg) =>
      msg.Content.Headers.Subject[0].includes('Confirm') ||
      msg.Content.Headers.Subject[0].includes('Verify')
    );

    expect(confirmationEmail).to.exist;

    const rawBody = confirmationEmail.Content.Body;
    const emailBody = rawBody.replace(/=\r?\n/g, '').replace(/=3D/g, '=');

    const linkMatch = emailBody.match(/<a href="(http:\/\/[^"]+)"[^>]*>Verify Email Address<\/a>/);
    const confirmationLink = linkMatch?.[1];

    expect(confirmationLink).to.exist;

    return confirmationLink;
  });
});

Cypress.Commands.add('loginUser', (email, password) => {
  cy.visit('/login');
  cy.get('[placeholder="Enter your email"]').type(email);
  cy.get('[placeholder="Enter your password"]').type(password);
  cy.get('.el-button').click();
  cy.contains('Dashboard Overview').should('exist');
});

Cypress.Commands.add('logoutUser', () => {
  cy.get('.logout-button').click(); // adjust selector
  cy.url().should('include', '/login');
});

Cypress.Commands.add('createRestaurant', (name) => {
  cy.visit('/restaurants/create');
  cy.get('[placeholder="Enter restaurant name"]').type(name);
  cy.get('.el-button').contains('Create').click();
  cy.contains(name).should('exist');
});

Cypress.Commands.add('deleteUser', (email) => {
  cy.visit('/admin/users'); // adjust based on your app
  cy.get(`tr:contains("${email}")`).find('.delete-button').click(); // adjust
  cy.contains(email).should('not.exist');
});