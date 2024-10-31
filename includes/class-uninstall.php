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


class Uninstall {

	public static function uninstall() {
		delete_option('staging-admin-style-settings');
		delete_option('staging-admin-index-settings');
	}

}
