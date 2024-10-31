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


namespace Staging_Admin_Style\Admin;


class Admin {

	private $options;
	private $dev;
	private $staging;


	public function __construct() {

		$this->options = get_option( 'staging-admin-style-settings', Admin_Settings::option_defaults( 'staging-admin-style-settings' ) );
		$regex         = '#(' . implode( '|', $this->options['dev'] ) . ')#';
		$this->dev     = (bool) preg_match( $regex, site_url() );
		if ( ! $this->dev ) {
			$regex         = '#(' . implode( '|', $this->options['staging'] ) . ')#';
			$this->staging = (bool) preg_match( $regex, site_url() );
		} else {
			$this->staging = false;
		}
	}

	public function noindex() {
		$options = get_option( 'staging-admin-index-settings', array( 'public' => 0, ) );
		if ( 1 == $options['public'] ) {
			if ( $this->dev || $this->staging ) {
				update_option( 'blog_public', 0 );
			} else {
				update_option( 'blog_public', 1 );
			}
		}
	}

	public function set_colour_scheme( $result ) {

		if ( $this->dev ) {
			$this->set_up_schemes();

			return $this->options['dev_scheme'];
		}
		if ( $this->staging ) {
			$this->set_up_schemes();

			return $this->options['staging_scheme'];
		}

		return $result;
	}

	private function set_up_schemes() {
		wp_admin_css_color(
			'staging-material',
			__( 'Material', 'set-admin-colour-on-staging-and-dev' ),
			plugins_url( "../css/colors/staging-material.min.css", __FILE__ ),
			array( '#3700B3', '#03DAC6', '#6200EE', '#B00020' )
		);
		wp_admin_css_color(
			'staging-neonpink',
			__( 'Neon Pink', 'set-admin-colour-on-staging-and-dev' ),
			plugins_url( "../css/colors/staging-neonpink.min.css", __FILE__ ),
			array( '#fe53bb', '#f5d300', '#9ebaa0', '#aa9d88' )
		);
	}

	public function settings_pages() {
		if ( $this->dev || $this->staging ) {
			$settings = new Admin_Settings();
			add_action( 'admin_menu', array( $settings, 'settings_setup' ) );
		}
	}


	public function display_admin_notices() {
		if ( $this->staging ) {
			$class   = 'notice notice-warning';
			$message = __( 'This is the STAGING site,  this is why the colour has changed !!!!! ', 'set-admin-colour-on-staging-and-dev' );
			printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', esc_attr( $class ), esc_html( $message ), site_url() );
		}
		if ( $this->dev ) {
			$class   = 'notice notice-warning';
			$message = __( 'This is the DEVELOPMENT site,  this is why the colour has changed !!!!! ', 'set-admin-colour-on-staging-and-dev' );
			printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', esc_attr( $class ), esc_html( $message ), site_url() );
		}
	}

	public function enqueue_styles() {
		if ( $this->dev ) {
			$options = get_option( 'staging-admin-style-settings' );
			wp_enqueue_style( 'set-admin-colour-on-staging-and-dev-admin-bar', plugins_url( "../css/colors/${options['dev_scheme']}-bar.min.css", __FILE__ ), array(), '2.1', 'all' );
		}
		if ( $this->staging ) {
			$options = get_option( 'staging-admin-style-settings' );
			wp_enqueue_style( 'set-admin-colour-on-staging-and-dev-admin-bar', plugins_url( "../css/colors/${options['staging_scheme']}-bar.min.css", __FILE__ ), array(), '2.1', 'all' );
		}
	}


}


