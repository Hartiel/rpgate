import { test, expect } from '@playwright/test';

test.describe('Theme Configuration and Persistence Flow', () => {

    test('should allow a logged in user to configure theme and persist settings across reloads', async ({ page }) => {
        // 1. Setup session by registering/logging in
        await page.goto('http://localhost/auth/register');
        
        // Use a unique email per test run
        const randomStr = Math.random().toString(36).substring(7);
        const email = `themeuser_${randomStr}@rpgate.com`;
        
        await page.fill('#name', 'Theme Test User');
        await page.fill('#username', `themeuser_${randomStr}`);
        await page.fill('#email', email);
        await page.fill('#password', 'Password!123');
        await page.fill('#password_confirmation', 'Password!123');
        await page.click('button[type="submit"]');

        // Verify successful registration by landing on the lobby
        await expect(page).toHaveURL('http://localhost/home');

        // 2. Navigate to Settings page
        await page.goto('http://localhost/settings');
        await expect(page.locator('h1:has-text("Realm Settings")')).toBeVisible();

        // 3. Test Light Theme
        await page.click('[data-testid="theme-light-btn"]');
        // The root <html> element should have the class "light" and not "dark"
        const htmlElement = page.locator('html');
        await expect(htmlElement).toHaveClass(/\blight\b/);
        await expect(htmlElement).not.toHaveClass(/\bdark\b/);

        // 4. Test Dark Theme
        await page.click('[data-testid="theme-dark-btn"]');
        await expect(htmlElement).toHaveClass(/\bdark\b/);
        await expect(htmlElement).not.toHaveClass(/\blight\b/);

        // 5. Test Persistence (Simulate F5 Reload)
        await page.reload();
        // Theme should still be dark after reload
        await expect(htmlElement).toHaveClass(/\bdark\b/);
        await expect(htmlElement).not.toHaveClass(/\blight\b/);

        // 6. Test System Theme
        await page.click('[data-testid="theme-system-btn"]');
        // Should have either light or dark depending on the browser/system setup
        const hasLightOrDark = await htmlElement.evaluate((el) => {
            return el.classList.contains('light') || el.classList.contains('dark');
        });
        expect(hasLightOrDark).toBe(true);
    });

});
