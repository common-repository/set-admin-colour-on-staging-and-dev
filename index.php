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

/**
 * Plugin Name:       Auto Set Admin Colour on Staging and Dev
 * Description:       Set Admin Colour on staging and dev sites automatically without impacting user settings
 * Version:           4.0.0
 * Plugin URI:        https://fullworks.net
 * Author:            Fullworks
 * Text Domain:       set-admin-colour-on-staging-and-dev
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Staging_Admin_Style;
use \Staging_Admin_Style\Includes\Core;
use \Staging_Admin_Style\Includes\Freemius_Config;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! function_exists( 'Staging_Admin_Style\run_Staging_Admin_Style' ) ) {
	define( 'STAGING_ADMIN_STYLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'STAGING_ADMIN_STYLE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
	require_once STAGING_ADMIN_STYLE_PLUGIN_DIR . 'includes/autoloader.php';
	function run_Staging_Admin_Style() {
		$freemius = new Freemius_Config();
		$freemius = $freemius->init();
		$freemius->add_action( 'after_uninstall', array( '\Staging_Admin_Style\Includes\Uninstall', 'uninstall' ) );
		do_action( 'sacosad_fs_loaded' );

		$plugin = new Core();
		$plugin->run();
	}
	run_Staging_Admin_Style();
} else {
	die( __( 'Cannot execute as the plugin already exists', 'set-admin-colour-on-staging-and-dev' ) );
}
