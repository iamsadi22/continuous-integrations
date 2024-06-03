/**
 * WordPress dependencies
 */
const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );

const { activatePlugin, page } = require('@wordpress/e2e-test-utils');


test.describe( 'Plugin Activation', () => {
	test.beforeAll( async ( { requestUtils } ) => {
		await requestUtils.activatePlugin(
			'continuous-integrations'
		);
	} );

	test( 'should activate the plugin and validate its dashboard', async ( { admin, page } ) => {

		try {
			await admin.visitAdminPage( 'admin.php?page=continuous-integrations#/' );
			await expect(page.getByText('Welcome to React boilerplate for WordPress plugin\n')).toBeVisible();
		} catch (error) {
			console.error('Error during test execution:', error);
			throw error; // Re-throw the error to fail the test
		}
	} );
} );
