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


class Admin_Pages {

	public $block_table_obj;
	protected $settings_page;

	protected $settings_page_id;

	protected $settings_title;

	public function __construct() {

	}

	public function settings_setup() {
		add_submenu_page(
			'options-general.php',
			$this->settings_title, /* Page Title */
			$this->settings_title,                       /* Menu Title */
			'manage_options',                 /* Capability */
			'set-admin-colour-on-staging-and-dev',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);
		$this->register_settings();
		$page_hook_id = $this->settings_page_id;
		if ( ! empty( $this->settings_page ) ) {
			/* Load the JavaScript needed for the settings screen. */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( "admin_footer-{$page_hook_id}", array( $this, 'footer_scripts' ) );
			/* Set number of column available. */
			add_filter( 'screen_layout_columns', array( $this, 'screen_layout_column' ), 10, 2 );
			add_action( $this->settings_page_id . '_settings_page_boxes', array( $this, 'add_required_meta_boxes' ) );
		}
	}

	public function register_settings() {
	}

	public function enqueue_scripts( $hook_suffix ) {
		$page_hook_id = $this->settings_page_id;
		if ( $hook_suffix == $page_hook_id ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
		}
	}

	public function footer_scripts() {
		$page_hook_id = $this->settings_page_id;
		$confmsg      = __( 'Are you sure want to do this?', 'widget-for-eventbrite-api' );
		?>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function ($) {
                // toggle
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                postboxes.add_postbox_toggles('<?php echo $page_hook_id; ?>');
                // display spinner
                $('#fx-smb-form').submit(function () {
                    $('#publishing-action .spinner').css('visibility', 'visible');
                });
                $('#delete-action input').on('click', function () {
                    return confirm('<?php echo $confmsg; ?>');
                });
            });
            //]]>
        </script>
		<?php
	}

	public function screen_layout_column( $columns, $screen ) {
		$page_hook_id = $this->settings_page_id;
		if ( $screen == $page_hook_id ) {
			$columns[ $page_hook_id ] = 2;
		}

		return $columns;
	}

	/**
	 *
	 */
	public function settings_page() {
		global $hook_suffix;
		if ( $this->settings_page_id == $hook_suffix ) {
			do_action( $this->settings_page_id . '_settings_page_boxes', $hook_suffix );
			?>
            <div class="wrap">
                <h2><?php echo $this->settings_title; ?></h2>
				<?php
				global $pagenow;
				if ( $pagenow !== "options-general.php" ) {
					settings_errors();
				} ?>
                <div class="fs-settings-meta-box-wrap">
                    <form id="fs-smb-form" method="post" action="options.php">
						<?php settings_fields( $this->option_group ); // options group
						?>
						<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
						<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
                        <div id="poststuff">
                            <div id="post-body"
                                 class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
                                <div id="postbox-container-1" class="postbox-container">
									<?php do_meta_boxes( $hook_suffix, 'side', null ); ?>
                                </div>
                                <div id="postbox-container-2" class="postbox-container">
									<?php do_meta_boxes( $hook_suffix, 'normal', null ); ?>
									<?php do_meta_boxes( $hook_suffix, 'advanced', null ); ?>
                                </div>
                            </div>
                            <br class="clear">
                        </div>
                    </form>
                </div>
            </div>
			<?php
		}
	}

	public function add_required_meta_boxes() {
		global $hook_suffix;
		if ( $this->settings_page_id == $hook_suffix ) {
			$this->add_meta_boxes();
			add_meta_box(
				'submitdiv',               /* Meta Box ID */
				__( 'Save Options', 'widget-for-eventbrite-api' ),            /* Title */
				array( $this, 'submit_meta_box' ),  /* Function Callback */
				$this->settings_page_id,                /* Screen: Our Settings Page */
				'side',                    /* Context */
				'high'                     /* Priority */
			);
		}
	}

	public function add_meta_boxes() {
	}

	public function submit_meta_box() {
		?>
        <div id="submitpost" class="submitbox">
            <div id="major-publishing-actions">
                <div id="delete-action">
                    <input type="submit" name="<?php echo "{$this->option_group}-reset"; ?>"
                           id="<?php echo "{$this->option_group}-reset"; ?>"
                           class="button"
                           value="Reset Settings">
                </div>
                <div id="publishing-action">
                    <span class="spinner"></span>
					<?php submit_button( esc_attr__( 'Save', 'widget-for-eventbrite-api' ), 'primary', 'submit', false ); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
		<?php
	}

	public function reset_sanitize( $settings ) {
		if ( ! empty( $settings ) ) {
			add_settings_error( $this->option_group, '', esc_html__( 'Settings reset to defaults.', 'set-admin-colour-on-staging-and-dev' ), 'updated' );
			$this->delete_options();
		}

		return $settings;
	}

	public function delete_options() {
	}

}
