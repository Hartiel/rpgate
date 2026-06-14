import { test, expect } from '@playwright/test';

test.describe('SPA Authentication Flow', () => {

    test('should register, login, persist session, and logout successfully', async ({ page }) => {
        // 1. Register

        // 1.1. navigate to register page
        await page.goto('http://localhost/auth/register');
        // 1.2. Fill the register form
        await page.fill('#name', 'User Test');
        await page.fill('#username', 'usertest');
        await page.fill('#email', 'usertest@rpgate.com');
        await page.fill('#password', 'Password!123');
        await page.fill('#password_confirmation', 'Password!123');
        // 1.3. Click the submit register form
        await page.click('button[type="submit"]');

        // 2. Login
        // 2.1. Navigate to the login page
        await page.goto('http://localhost/auth/login');

        // 2.2. Fill the login form
        await page.fill('#email', 'usertest@rpgate.com');
        await page.fill('#password', 'Password!123');

        // 2.3. Click the submit login button
        await page.click('button[type="submit"]');

        // 3. Persistent Data
        // 3.1. Verify if the Router allowed access to the Dashboard
        await expect(page).toHaveURL('http://localhost/home');
        await expect(page.locator('h1:has-text("The Grand Lobby")')).toBeVisible();

        // 3.2. Reload the page (Simulate F5)
        await page.reload();

        // 3.3. Verify if the user remained logged
        await expect(page).toHaveURL('http://localhost/home');
        await expect(page.locator('h1:has-text("The Grand Lobby")')).toBeVisible();

        // 4. Logout
        // 4.1 Click the logout button
        await page.click('button[aria-label="Logout Button"]');

        // 4.2. Verify if the system destroyed the session and redirected to Login
        await expect(page).toHaveURL(/\/auth\/login/);

        // 4.3 Clear cookies session
        await page.context().clearCookies();

        // 4.4. Attempt to force entry directly via URL (Router Guard Test)
        await page.goto('http://localhost/home');
        await expect(page).toHaveURL(/\/auth\/login/);
    });

});
