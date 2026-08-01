import { test, expect } from '@playwright/test';

test.describe('Friendship System E2E Flow', () => {

    test('should allow user to navigate to friends page, search for players, and see lists', async ({ page }) => {
        // 1. Setup session by registering
        await page.goto('http://localhost/auth/register');
        
        const randomStr = Math.random().toString(36).substring(7);
        const email = `friendsuser_${randomStr}@rpgate.com`;
        
        await page.fill('#name', 'Friends Test User');
        await page.fill('#username', `friendsuser_${randomStr}`);
        await page.fill('#email', email);
        await page.fill('#password', 'Password!123');
        await page.fill('#password_confirmation', 'Password!123');
        await page.click('button[type="submit"]');

        // Verify successful registration by landing on the lobby
        await expect(page).toHaveURL('http://localhost/home');

        // 2. Navigate to Friends page via navigation bar or direct navigation
        await page.goto('http://localhost/friends');
        await expect(page.locator('h1:has-text("The Social Guild")')).toBeVisible();

        // 3. Verify tabs existence
        await expect(page.locator('button:has-text("Guild Members")')).toBeVisible();
        await expect(page.locator('button:has-text("Pending Requests")')).toBeVisible();
        await expect(page.locator('button:has-text("Discover Players")')).toBeVisible();

        // 4. Switch to Discover Players and run a search
        await page.click('button:has-text("Discover Players")');
        await page.fill('input[placeholder="Search by name or username..."]', 'NonExistentAdventurer');
        await page.click('button:has-text("Search")');
        
        // 5. Verify the empty state message
        await expect(page.locator('text=No Guilds Found')).toBeVisible();
    });

});
