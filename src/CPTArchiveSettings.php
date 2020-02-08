<?php

/**
 * The admin helper file.
 *
 * This file is for doing admin markup.
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category   WPS\WP\Plugins\Team
 * @package    WPS\WP\Plugins\Team
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://wpsmith.net/
 * @since      0.0.1
 */

namespace WPS\WP\Genesis;

if ( ! class_exists( __NAMESPACE__ . '\CPTArchiveSettings' ) ) {
	/**
	 * Class CPTArchiveSettings
	 * @package WPS\WP\Genesis
	 */
	class CPTArchiveSettings extends \Genesis_Admin_CPT_Archive_Settings {

		/**
		 * Include the necessary sortable meta box scripts.
		 *
		 * @since 1.8.0
		 */
		public function scripts() {
			wp_enqueue_media();
			parent::scripts();

		}

		/**
		 * Register each of the settings with a sanitization filter type.
		 *
		 * @see \Genesis_Settings_Sanitizer::add_filter()
		 */
		public function sanitizer_filters() {

			parent::sanitizer_filters();

			genesis_add_option_filter(
				'url',
				$this->settings_field,
				array(
					'headline_image',
				)
			);
			genesis_add_option_filter(
				'absint',
				$this->settings_field,
				array(
					'headline_image_id',
				)
			);
			genesis_add_option_filter(
				'no_html',
				$this->settings_field,
				array(
					'archive_image_size',
				)
			);

		}

		/**
		 * Echo out the content of a meta box.
		 *
		 * @param object $object Object passed to do_meta_boxes function.
		 * @param array $meta_box Array of parameters passed to add_meta_box function.
		 */
		public function do_meta_box( $object, $meta_box ) {

			if ( 'genesis-cpt-archives-settings' !== $meta_box['id'] ) {
				$view = $this->views_base . '/meta-boxes/' . $meta_box['id'] . '.php';
				if ( is_file( $view ) ) {
					include $view;
				}
			} else {
				include __DIR__ . '/views/cpt-archives-settings.php';
			}

		}

	}
}