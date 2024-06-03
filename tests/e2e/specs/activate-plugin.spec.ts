/**
 * WordPress dependencies
 */
const { activatePlugin, test, expect } = require( '@wordpress/e2e-test-utils-playwright' );


test.describe( 'Plugin Activation', () => {
	test( 'should activate the plugin and validate its dashboard', async ( { admin, page } ) => {
		// Activate the plugin
		await activatePlugin(page, 'continuous-integrations');

		await admin.visitAdminPage( 'admin.php?page=continuous-integrations#/' );

		await expect(page.getByText('Welcome to React boilerplate for WordPress plugin\n')).toBeVisible();

	} );
} );
