// cypress/support/utils.js
let cachedUser = null;

export function getTestUser() {
  if (cachedUser) return cachedUser;

  cachedUser = {
    username: `user`,
    email: `user@gmail.com`,
    password: 'password',
  };

  return cachedUser;
}
