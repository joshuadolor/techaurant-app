describe('Restaurant visibility per user', () => {
  const user1 = {
    username: 'testuser811',
    email: 'testuser811@gmail.com',
    password: 'password'
  };

  const user2 = {
    username: 'testuser812',
    email: 'testuser812@gmail.com',
    password: 'password'
  };

  const restaurantDetails = {
    name: 'user1_restaurant',
    tagline: 'user1_tagline',
    description: 'user1_description',
    image: 'google.png',
    phone: 'user1_number',
    address: 'user1_address'
  };

  context('User 1 Flow - Register, Verify, Create Restaurant', () => {
    it('registers, verifies, logs in, and creates a restaurant', () => {
      cy.registerUser(user1.username, user1.email, user1.password);
      cy.verifyEmailFromMailhog();
      cy.loginUser(user1.email, user1.password);

      // Navigate to restaurant section and create one
      cy.get(':nth-child(3) > .mt-2 > li > .flex > :nth-child(2)').click();
      cy.get('.el-button > span').click(); // Add restaurant button

      cy.get('[placeholder="Enter your restaurant name"]').type(restaurantDetails.name);
      cy.get('[placeholder="A short description of your restaurant"]').type(restaurantDetails.tagline);
      cy.get('[placeholder="Tell customers about your restaurant"]').type(restaurantDetails.description);
      cy.get('input[type="file"]').attachFile(restaurantDetails.image);
      cy.wait(1000);
      cy.contains('button', 'Save').click();
      cy.get('[placeholder="+1 (555) 555-5555"]').type(restaurantDetails.phone);
      cy.get('[placeholder="Enter your restaurant address"]').type(restaurantDetails.address);
      cy.get('button[type="submit"]').click();

      // Logout
      cy.get(':nth-child(4) > .mt-2 > :nth-child(1) > .flex').click();
      cy.url().should('include', '/account');
      cy.get('.bg-blue-500').click();
      cy.contains('Logged out successfully!');
    });
  });

  context('User 2 Flow - Register, Verify, Login and Confirm No Restaurant Visibility', () => {
    it('registers, verifies and logs in second user', () => {
      cy.registerUser(user2.username, user2.email, user2.password);
      cy.verifyEmailFromMailhog();
      cy.loginUser(user2.email, user2.password);

      // Check restaurant list should be empty
      cy.get(':nth-child(3) > .mt-2 > li > .flex > :nth-child(2)').click();
      cy.contains('No items found');
    });
  });
});
