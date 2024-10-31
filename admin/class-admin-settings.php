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

class Admin_Settings extends Admin_Pages {

	protected $settings_page;
	protected $settings_page_id = 'settings_page_set-admin-colour-on-staging-and-dev';
	protected $option_group = 'set-admin-colour-on-staging-and-dev';


	public function __construct() {


		$this->settings_title = esc_html__( 'Staging & Dev Colours', 'set-admin-colour-on-staging-and-dev' );
		parent::__construct();
	}

	public function register_settings() {
		register_setting(
			$this->option_group,                         /* Option Group */
			'staging-admin-style-settings',                   /* Option Name */
			array( $this, 'sanitize_style_settings' )          /* Sanitize Callback */
		);
		register_setting(
			$this->option_group,                         /* Option Group */
			'staging-admin-index-settings',                   /* Option Name */
			array( $this, 'sanitize_index_settings' )          /* Sanitize Callback */
		);
		$this->settings_page = add_submenu_page(
			'set-admin-colour-on-staging-and-dev',
			'Settings', /* Page Title */
			'Settings',                       /* Menu Title */
			'manage_options',                 /* Capability */
			'set-admin-colour-on-staging-and-dev',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		register_setting(
			$this->option_group,                         /* Option Group */
			"{$this->option_group}-reset",                   /* Option Name */
			array( $this, 'reset_sanitize' )          /* Sanitize Callback */
		);

	}

	public function reset_sanitize( $settings ) {
		// Detect multiple sanitizing passes.
		// Accomodates bug: https://core.trac.wordpress.org/ticket/21989


		if ( ! empty( $settings ) ) {
			add_settings_error( $this->option_group, '', 'Settings reset to defaults.', 'updated' );
			/* Delete Option */
			$this->delete_options();

		}

		return $settings;
	}

	public function delete_options() {
		update_option( 'staging-admin-style-settings', self::option_defaults( 'staging-admin-style-settings' ) );
		update_option( 'staging-admin-index-settings', self::option_defaults( 'staging-admin-index-settings' ) );
	}

	public static function option_defaults( $option ) {
		switch ( $option ) {
			case 'staging-admin-style-settings':
				return array(
					'dev_scheme'     => 'staging-material',
					'staging_scheme' => 'staging-neonpink',
					'dev'            => array(
						'localhost',
						'.*\.dev',
						'.*\.dev\.cc',
						'.*\.local',
						'\.local',
						'\.dev',
						'local\..*',
						'dev\..*',
						'dev-.*\.pantheonsite.io', //(Pantheon)
					),
					'staging'        => array(
						'.*\.test',
						'.*\.staging',
						'.*\.example',
						'.*\.invalid',
						'.*\.myftpupload.com', // (GoDaddy)
						'.*\..cloudwaysapps.com', //(Cloudways)
						'.*\.ngrok.io', // (tunneling)
						'staging.*\.',
						'test\..*',
						'staging.*\..*',
						'.*\.staging.wpengine.com', // (WP Engine)
						'test-.*\.pantheonsite.io', // (Pantheon)
					),
				);
			case 'staging-admin-index-settings':
			    return array (
			            'public' => 1,
                );
			default:
				return false;
		}
	}


	public function add_meta_boxes() {
		add_meta_box(
			'info',                  /* Meta Box ID */
			__( 'About', 'set-admin-colour-on-staging-and-dev' ),               /* Title */
			array( $this, 'meta_box_info' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		$this->add_meta_box(
			'index',                  /* Meta Box ID */
			__( 'Search Engine Visibility', 'set-admin-colour-on-staging-and-dev' ),               /* Title */
			array( $this, 'meta_box_index' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		$this->add_meta_box(
			'domains',                  /* Meta Box ID */
			__( 'Domains', 'set-admin-colour-on-staging-and-dev' ),               /* Title */
			array( $this, 'meta_box_domains' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		$this->add_meta_box(
			'schemes',                  /* Meta Box ID */
			__( 'Colour Schemes', 'set-admin-colour-on-staging-and-dev' ),               /* Title */
			array( $this, 'meta_box_schemes' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
	}

	private function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null, $closed = false ) {
		add_meta_box(
			$id,
			$title,
			$callback,
			$screen,
			$context,
			$priority,
			$callback_args
		);
		if ( ! isset( $_GET['settings-updated'] ) ) {
			if ( $closed ) {
				add_filter( "postbox_classes_{$screen}_{$id}", function ( $classes ) {
					array_push( $classes, 'closed' );

					return $classes;
				} );
			}
		}
	}

	public function meta_box_info() {
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Information', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>
					<?php _e( '<p>This plugin will detect certain domains as staging or development, and apply the selected, non standard colour scheme to admin. This colour scheme will not be saved with the user data, so when you release to production the default user settings will be used.
    </p>', 'set-admin-colour-on-staging-and-dev' );
					?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Support', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>
					<?php _e( '<p>For support visit <a class="button-secondary" href="https://wordpress.org/support/plugin/set-admin-colour-on-staging-and-dev/" target="_blank">WordPress.org.</a></p>
', 'set-admin-colour-on-staging-and-dev' );
					?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function meta_box_index() {
		$options               = get_option( 'staging-admin-index-settings' );
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Search Engine Visibility', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>
                    <label for="staging-admin-index-settings[public]"><input type="checkbox"
                                                                      name="staging-admin-index-settings[public]"
                                                                      id="staging-admin-index-settings[public]"
                                                                      value="1"
			                <?php checked( '1', $options['public'] ); ?>>
                    <p>
                        <span class="description"><?php _e( 'If checked, will automatically disable ( set noindex) search engine visibility for staging and dev, but will AUTOMATICALLY enable for the  live site<br>unchecked will leave the setting alone', 'set-admin-colour-on-staging-and-dev' ); ?></span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function meta_box_schemes() {
		global $_wp_admin_css_colors;
		$schemes               = array(
			'staging-material',
			'staging-neonpink',
		);
		$options               = get_option( 'staging-admin-style-settings' );
		$staging_current_color = $options['staging_scheme'];
		$dev_current_color     = $options['dev_scheme'];
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Staging Schemes', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>

                    <fieldset id="color-picker" class="scheme-list">
                        <legend class="screen-reader-text"><span><?php _e( 'Admin Color Scheme' ); ?></span></legend>
						<?php
						wp_nonce_field( 'save-color-scheme', 'color-nonce', false );
						foreach ( $_wp_admin_css_colors as $color => $color_info ) {
							/*
							if ( ! in_array( $color, $schemes ) ) {
								continue;
							}
							*/

							?>
                            <div class="color-option <?php echo ( $color == $staging_current_color ) ? 'selected' : ''; ?>">
                                <input name="staging-admin-style-settings[staging_scheme]"
                                       id="admin_color_<?php echo esc_attr( $color ); ?>" type="radio"
                                       value="<?php echo esc_attr( $color ); ?>"
                                       class="tog" <?php checked( $color, $staging_current_color ); ?> />
                                <input type="hidden" class="css_url"
                                       value="<?php echo esc_url( $color_info->url ); ?>"/>
                                <input type="hidden" class="icon_colors"
                                       value="<?php echo esc_attr( wp_json_encode( array( 'icons' => $color_info->icon_colors ) ) ); ?>"/>
                                <label for="admin_color_<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $color_info->name ); ?></label>
                                <table class="color-palette">
                                    <tr>
										<?php

										foreach ( $color_info->colors as $html_color ) {
											?>
                                            <td style="background-color: <?php echo esc_attr( $html_color ); ?>">
                                                &nbsp;
                                            </td>
											<?php
										}

										?>
                                    </tr>
                                </table>
                            </div>
							<?php
						}

						?>
                    </fieldset>
                    <p>
                        <span class="description"><?php _e( 'The selected scheme will be automatically applied on staging domains', 'set-admin-colour-on-staging-and-dev' ); ?></span>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Development Schemes', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>

                    <fieldset id="color-picker" class="scheme-list">
                        <legend class="screen-reader-text"><span><?php _e( 'Admin Color Scheme' ); ?></span></legend>
						<?php
						wp_nonce_field( 'save-color-scheme', 'color-nonce', false );
						foreach ( $_wp_admin_css_colors as $color => $color_info ) {
							/*
							if ( ! in_array( $color, $schemes ) ) {
								continue;
							}
							*/

							?>
                            <div class="color-option <?php echo ( $color == $dev_current_color ) ? 'selected' : ''; ?>">
                                <input name="staging-admin-style-settings[dev_scheme]"
                                       id="admin_color_<?php echo esc_attr( $color ); ?>" type="radio"
                                       value="<?php echo esc_attr( $color ); ?>"
                                       class="tog" <?php checked( $color, $dev_current_color ); ?> />
                                <input type="hidden" class="css_url"
                                       value="<?php echo esc_url( $color_info->url ); ?>"/>
                                <input type="hidden" class="icon_colors"
                                       value="<?php echo esc_attr( wp_json_encode( array( 'icons' => $color_info->icon_colors ) ) ); ?>"/>
                                <label for="admin_color_<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $color_info->name ); ?></label>
                                <table class="color-palette">
                                    <tr>
										<?php

										foreach ( $color_info->colors as $html_color ) {
											?>
                                            <td style="background-color: <?php echo esc_attr( $html_color ); ?>">
                                                &nbsp;
                                            </td>
											<?php
										}

										?>
                                    </tr>
                                </table>
                            </div>
							<?php
						}

						?>
                    </fieldset>
                    <p>
                        <span class="description"><?php _e( 'The selected scheme will be automatically applied on development domains', 'set-admin-colour-on-staging-and-dev' ); ?></span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function meta_box_domains() {
		$options = get_option( 'staging-admin-style-settings' );
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Staging Domains', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>
                    <textarea class="large-text"
                              rows="<?php echo ( count( $options['staging'] ) < 12 ) ? count( $options['staging'] ) + 2 : count( $options['staging'] ); ?>"
                              name="staging-admin-style-settings[staging]"
                              id="staging-admin-style-settings[staging]"><?php
	                    echo esc_html( implode( PHP_EOL, $options['staging'] ) );
	                    ?></textarea>
                    <p>
                        <span class="description"><?php _e( 'Enter Regular Expressions to match your staging domain, one per line if multiple', 'set-admin-colour-on-staging-and-dev' ); ?></span>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Development Domains', 'set-admin-colour-on-staging-and-dev' ); ?></th>
                <td>
                    <textarea class="large-text"
                              rows="<?php echo ( count( $options['dev'] ) < 12 ) ? count( $options['dev'] ) + 2 : count( $options['dev'] ); ?>"
                              name="staging-admin-style-settings[dev]"
                              id="staging-admin-style-settings[dev]"><?php
	                    echo esc_html( implode( PHP_EOL, $options['dev'] ) );
	                    ?></textarea>
                    <p>
                        <span class="description"><?php _e( 'Enter Regular Expressions to match your development domain, one per line if multiple', 'set-admin-colour-on-staging-and-dev' ); ?></span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}
	public function sanitize_index_settings( $settings ) {
		if ( isset( $_REQUEST['set-admin-colour-on-staging-and-dev-reset'] ) ) {
			return $settings;
		}
		if ( ! isset( $settings['public'] ) ) {
			$settings['public'] = 0;
		}
		return $settings;
	}

	public function sanitize_style_settings( $settings ) {
		if ( isset( $_REQUEST['set-admin-colour-on-staging-and-dev-reset'] ) ) {
			return $settings;
		}

		$options = get_option( 'staging-admin-style-settings' );


		if ( isset( $settings['dev'] ) && ( ! empty( $settings['dev'] ) ) ) {
			$settings['dev'] = array_filter( preg_split( '/\R/', sanitize_textarea_field( $settings['dev'] ) ) );
			if ( false === @preg_match( '#(' . implode( '|', $settings['dev'] ) . ')#', site_url() ) ) {
				add_settings_error(
					'devstagc',
					'devstagcdev',
					sprintf( __( 'Development domains contain invalid Regular Expressions, not saved', 'set-admin-colour-on-staging-and-dev' ) ),
					'error'
				);
				$settings['dev'] = $options['dev'];
			}
		}

		if ( isset( $settings['staging'] ) && ( ! empty( $settings['staging'] ) ) ) {
			$settings['staging'] = array_filter( preg_split( '/\R/', sanitize_textarea_field( $settings['staging'] ) ) );
			$x                   = '#(' . implode( '|', $settings['staging'] ) . ')#';
			if ( false === @preg_match( '#(' . implode( '|', $settings['staging'] ) . ')#', site_url() ) ) {
				add_settings_error(
					'devstagc',
					'devstagcstage',
					sprintf( __( 'Staging domains contain invalid Regular Expressions, not saved', 'set-admin-colour-on-staging-and-dev' ) ),
					'error'
				);
				$settings['staging'] = $options['staging'];
			}
		}
		$intersect = array_intersect( $settings['staging'], $settings['dev'] );
		if ( ! empty ( $intersect ) ) {
			add_settings_error(
				'devstagc',
				'devstagcoverlap',
				sprintf( __( 'Staging domains and Development domain are overlapped, domain not saved', 'set-admin-colour-on-staging-and-dev' ) ),
				'error'
			);
			$settings['staging'] = $options['staging'];
			$settings['dev']     = $options['dev'];
		}

		if ( $settings['dev_scheme'] === $settings['staging_scheme'] ) {
			add_settings_error(
				'devstagc',
				'devstagcovsame',
				sprintf( __( 'Staging colours and Development colours are the same, colour selection not saved', 'set-admin-colour-on-staging-and-dev' ) ),
				'error'
			);
			$settings['staging_scheme'] = $options['staging_scheme'];
			$settings['dev_scheme']     = $options['dev_scheme'];
		}


		return $settings;
	}
}

