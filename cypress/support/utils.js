// cypress/support/utils.js
let cachedUser = null;

export function getTestUser() {
  if (cachedUser) return cachedUser;

  const timestamp = Date.now();
  cachedUser = {
    username: `user_${timestamp}`,
    email: `user_${timestamp}@gmail.com`,
    password: 'password',
  };

  return cachedUser;
}
