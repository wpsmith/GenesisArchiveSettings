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

if ( ! class_exists( __NAMESPACE__ . '\TaxArchiveSettings' ) ) {
	/**
	 * Class TaxArchiveSettings
	 * @package WPS\WP\Plugins\Partners\Admin
	 */
	class TaxArchiveSettings extends \Genesis_Admin_Meta_Boxes {

		/**
		 * TaxArchiveSettings constructor.
		 */
		public function __construct() {

			parent::__construct();
			add_filter( 'genesis_term_meta_defaults', function ( $defaults ) {
				$defaults['headline_image']    = '';
				$defaults['headline_image_id'] = '';
			} );

		}

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
		 * Echo out the content of a meta box.
		 *
		 * @param string $id Id of the meta box.
		 * @param object $object Object for the meta box. Default null.
		 */
		public function show_meta_box( $id, $object = null ) {

			parent::show_meta_box( $id, $object );

		}

		/**
		 * Echo out the content of a meta box.
		 *
		 * @param object $object Object passed to do_meta_boxes function.
		 * @param array $meta_box Array of parameters passed to add_meta_box function.
		 */
		public function do_meta_box( $object, $meta_box ) {

			if ( 'genesis-term-meta-settings' !== $meta_box['id'] ) {
				$view = $this->views_base . '/meta-boxes/' . $meta_box['id'] . '.php';
				if ( is_file( $view ) ) {
					include $view;
				}
			} else {
				include __DIR__ . '/views/term-meta-settings.php';
			}

		}

	}
}