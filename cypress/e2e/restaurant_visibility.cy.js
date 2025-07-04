describe('Restaurant visibility per user', () => {
  const username = 'testuser1';
  const email = 'testuser1@gmail.com';
  const password = 'password';

  let confirmationLink;

  describe('Registration Page', () => {
    it('registers a new user account', () => {
      cy.registerUser(username, email, password);
    });
  });

  describe('MailHog Verification Page', () => {
    it('fetches the confirmation email and gets the Verify Email Address link', () => {
      cy.getLatestEmailConfirmationLink().then((link) => {
        confirmationLink = link;
      });
    });

    it('visits the confirmation link to activate the user', () => {
      cy.visit('http://localhost:8025/');
      console.log('registerUser got:', { username, email, password });

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
  });

  describe.only('Login Page', () => {
    it('logs in the confirmed user', () => {
      cy.visit('http://localhost:8000/login');
      cy.get('[placeholder="Enter your email"]').type(email);
      cy.get('[placeholder="Enter your password"]').type(password);
      cy.get('.el-button').click();

      cy.get('.text-2xl.font-bold').should('contain.text', 'Dashboard Overview');

      
      cy.get(':nth-child(3) > .mt-2 > li > .flex > :nth-child(2)').click(); //opens the restaurant
      cy.get('.el-button > span').click(); //clicks the add restaurant button
      cy.get('[placeholder="Enter your restaurant name"]').type('user1_restaurant')
      cy.get('[placeholder="A short description of your restaurant"]').type('user1_tagline')
      cy.get('[placeholder="Tell customers about your restaurant"]').type('user1_description')
      cy.get('input[type="file"]').attachFile('google.png')
      cy.contains('button', 'Save').should('exist')
      cy.wait(500)
      cy.contains('button', 'Save').click();
      cy.get('[placeholder="+1 (555) 555-5555"]').type('user1_number')
      cy.get('[placeholder="Enter your restaurant address"]').type('user1_address')
      cy.get('button[type="submit"]').click();





      
    });
  });


});