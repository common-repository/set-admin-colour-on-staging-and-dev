<?php
/**
 * @copyright (c) 2019.
 * @author            Alan Fuller (support@fullworks.net)
 * @licence           GPL V2 http://www.gnu.org/licenses/gpl-2.0.html
 * @link                  https://fullworks.net
 *
 * This file is part of Set Admin Colour on Staging and Dev plugin.
 *
 *    Set Admin Colour on Staging and Dev plugin is free software:
 *    you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Set Admin Colour on Staging and Dev plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with   Set Admin Colour on Staging and Dev plugin.  http://www.gnu.org/licenses/gpl-2.0.html
 *
 */


namespace Staging_Admin_Style\Includes;

use Staging_Admin_Style\Admin\Admin;
use Staging_Admin_Style\Admin\Admin_Settings;




class Core {

	public function __construct() {
	}

	public function run() {

		$this->set_locale();
		$this->set_options_data();
		$this->define_admin_hooks();
	}


	private function set_locale() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'set-admin-colour-on-staging-and-dev',
			false,
			STAGING_ADMIN_STYLE_PLUGIN_DIR . 'languages/'
		);
	}

	private function set_options_data() {
		if ( ! get_option( 'staging-admin-style-settings' ) ) {
			update_option( 'staging-admin-style-settings', Admin_Settings::option_defaults( 'staging-admin-style-settings' ) );
		}
		if ( ! get_option( 'staging-admin-index-settings' ) ) {
			update_option( 'staging-admin-index-settings', Admin_Settings::option_defaults( 'staging-admin-index-settings' ) );
		}
	}




	private function define_admin_hooks() {
		$plugin_admin = new Admin();
		add_filter( 'get_user_option_admin_color', array( $plugin_admin, 'set_colour_scheme' ), 9999, 1 );
		add_action( 'admin_notices', array( $plugin_admin, 'display_admin_notices' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'init', array( $plugin_admin, 'settings_pages' ) );
		add_action( 'admin_init', array( $plugin_admin, 'noindex' ) );
	}
}
