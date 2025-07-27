import 'cypress-file-upload';

Cypress.Commands.add('registerUser', (username, email, password) => {
  cy.visit('http://localhost:8000/register');
  cy.get('[placeholder="Enter your name"]').type(username);
  cy.get('[placeholder="Enter your email"]').type(email);
  cy.get('[placeholder="Enter your password"]').type(password);
  cy.get('[placeholder="Confirm your password"]').type(password);
  cy.get('.el-button').click();
  cy.get('.el-notification__title', { timeout: 5000 }).should('contain', 'Success');
});

Cypress.Commands.add('getLatestEmailConfirmationLink', () => {
  return cy.request('http://localhost:8025/api/v2/messages').then((response) => {
    const messages = response.body.items;
    const confirmationEmail = messages.find(msg =>
      msg.Content.Headers.Subject[0].includes('Confirm') ||
      msg.Content.Headers.Subject[0].includes('Verify')
    );

    expect(confirmationEmail).to.exist;

    const rawBody = confirmationEmail.Content.Body;
    const emailBody = rawBody.replace(/=\r?\n/g, '').replace(/=3D/g, '=');
    const linkMatch = emailBody.match(/http:\/\/localhost:8000\/verify-email\/[^\s"]+/);

    const confirmationLink = linkMatch?.[0];
    expect(confirmationLink).to.exist;
    return confirmationLink;
  });
});

Cypress.Commands.add('verifyEmailFromMailhog', () => {
  cy.getLatestEmailConfirmationLink().then((link) => {
    cy.visit(link, { failOnStatusCode: false });
    cy.url({ timeout: 10000 }).should('include', '/login');
  });
});

Cypress.Commands.add('loginUser', (email, password) => {
  cy.visit('http://localhost:8000/login');
  cy.get('[placeholder="Enter your email"]').type(email);
  cy.get('[placeholder="Enter your password"]').type(password);
  cy.get('.el-button').click();
  cy.get('.text-2xl.font-bold').should('contain.text', 'Dashboard Overview');
});

Cypress.Commands.add('logoutUser', () => {
  cy.get('.bg-blue-500').click();
  cy.contains('Logged out successfully!');
});