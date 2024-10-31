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
 * Class to load freemius configuration
 */

namespace Staging_Admin_Style\Includes;


class Freemius_Config {

	/**
	 * @return \Freemius
	 * @throws \Freemius_Exception
	 */
	public function init() {
		/** @var \Freemius $fullworks_security_fs Freemius global object. */
		global $sacosad_fs;

		if ( ! isset( $sacosad_fs ) ) {
			// Include Freemius SDK.
			require_once dirname(__FILE__) . '/vendor/freemius/wordpress-sdk/start.php';

			$sacosad_fs = fs_dynamic_init( array(
				'id'                  => '4583',
				'slug'                => 'set-admin-colour-on-staging-and-dev',
				'type'                => 'plugin',
				'public_key'          => 'pk_27fc2f53b41d02a376f5557f91a67',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'set-admin-colour-on-staging-and-dev',
					'account'        => false,
					'contact'        => false,
					'support'        => false,
					'parent'         => array(
						'slug' => 'options-general.php',
					),
				),
			) );
		}


		return $sacosad_fs;
	}
}
