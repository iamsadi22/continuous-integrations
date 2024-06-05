<?php

namespace RexTheme\ContinuousIntegrations\Admin;

class Menu {

	/**
	 * Admin constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'init_menu' ] );
	}

	/**
	 * Init menu
	 *
	 * @since 1.0.0
	 */
	public function init_menu() {
		global $submenu;

		$slug          = CONTINUOUS_INTEGRATIONS_SLUG;
		$menu_position = 50;
		$capability    = 'manage_options';

		add_menu_page( esc_attr__( 'Continuous Integrations', 'continuous-integrations' ), esc_attr__( 'Continuous Integrations', 'continuous-integrations' ), $capability, $slug, [ $this, 'plugin_page' ], '', $menu_position );

		if ( current_user_can( $capability ) ) {
			$submenu[ $slug ][] = [ esc_attr__( 'Home', 'continuous-integrations' ), $capability, 'admin.php?page=' . $slug . '#/' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$submenu[ $slug ][] = [ esc_attr__( 'Custom', 'continuous-integrations' ), $capability, 'admin.php?page=' . $slug . '#/custom' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}


	/**
	 * Render the plugin page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function plugin_page() {
		require_once CONTINUOUS_INTEGRATIONS_TEMPLATE_PATH . '/app.php';
	}
}
