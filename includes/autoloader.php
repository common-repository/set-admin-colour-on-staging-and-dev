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
 * Autoloader based on tutorial by
 * Tom McFarlin https://tommcfarlin.com/  Licensed    GPL-2.0+
 */

spl_autoload_register( 'fullworks_Staging_Admin_Style_autoload' );

function fullworks_Staging_Admin_Style_autoload( $class_name ) {


	// If the specified $class_name does not include our namespace, duck out.
	if ( false === strpos( $class_name, 'Staging_Admin_Style' ) ) {
		return;
	}

	// Split the class name into an array to read the namespace and class.
	$file_parts = explode( '\\', $class_name );

	// Do a reverse loop through $file_parts to build the path to the file.
	$namespace = '';
	for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

		// Read the current component of the file part.
		$current = strtolower( $file_parts[ $i ] );
		$current = str_ireplace( '_', '-', $current );

		// If we're at the first entry, then we're at the filename.
		if ( count( $file_parts ) - 1 === $i ) {

			/* If 'interface' is contained in the parts of the file name, then
			 * define the $file_name differently so that it's properly loaded.
			 * Otherwise, just set the $file_name equal to that of the class
			 * filename structure.
			 */
			if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {

				// Grab the name of the interface from its qualified name.
				$interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
				$interface_name = $interface_name[0];

				$file_name = "interface-$interface_name.php";

			} else {
				$file_name = "class-$current.php";
			}
		} else {
			$namespace = '/' . $current . $namespace;
		}
	}

	// Now build a path to the file using mapping to the file location.
	$filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
	$filepath .= $file_name;

	// If the file exists in the specified path, then include it.
	if ( file_exists( $filepath ) ) {
		include_once( $filepath );
	} else {
		// changed to be translatable
		wp_die(sprintf(__('The system file attempting to be loaded at %1$s does not exist.','set-admin-colour-on-staging-and-dev'),esc_html($filepath)) );
	}
}