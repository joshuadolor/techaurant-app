import { getTestUser } from '../support/utils';

describe('Restaurant visibility per user', () => {
  const { username, email, password } = getTestUser();

  let confirmationLink;



  it('registers a new user account', () => {
    cy.registerUser(username, email, password); // called from support/commands.js
  });

  it('fetches the confirmation email and gets the Verify Email Address link', () => {
    cy.wait(2000);

    cy.request('http://localhost:8025/api/v2/messages').then((response) => {
      const messages = response.body.items;

      const confirmationEmail = messages.find((msg) =>
        msg.Content.Headers.Subject[0].includes('Confirm') ||
        msg.Content.Headers.Subject[0].includes('Verify')
      );

      expect(confirmationEmail).to.exist;

      const rawBody = confirmationEmail.Content.Body;
      const emailBody = rawBody.replace(/=\r?\n/g, '').replace(/=3D/g, '=');

      const linkMatch = emailBody.match(/<a href="(http:\/\/[^"]+)"[^>]*>Verify Email Address<\/a>/);
      confirmationLink = linkMatch?.[1];

      expect(confirmationLink).to.exist;
    });
  });

  it('visits the confirmation link to activate the user', () => {
    cy.visit('http://localhost:8025/');
    console.log('registerUser got:', { username, email, password }); // Debug

    cy.get('.messages > :nth-child(1)').click();
    cy.get('.content').should('contain.text', 'verify-email');

    cy.get('.content')
      .invoke('text')
      .then((body) => {
        const cleaned = body.replace(/=\r?\n/g, '').replace(/=3D/g, '=');
        const match = cleaned.match(/http:\/\/localhost:8000\/verify-email\/[^\s]+/);
        confirmationLink = match?.[0];
        expect(confirmationLink).to.exist;

        cy.visit(confirmationLink, { failOnStatusCode: false });
        cy.url({ timeout: 10000 }).should('include', '/login');
        cy.wait(1000);
      });
  });

  it('logs in the confirmed user', () => {
    cy.visit('http://localhost:8000/login');
    console.log('registerUser got:', { username, email, password }); // Debug

    cy.get('[placeholder="Enter your email"]').type(email);
    cy.get('[placeholder="Enter your password"]').type(password);
    cy.get('.el-button').click();

    cy.contains('Welcome').should('exist');
  });
});
