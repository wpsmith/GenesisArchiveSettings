<?php

/**
 * The archive settings file for custom post types and taxonomy terms.
 *
 * Enables an enhanced area for custom post types archives and taxonomy term archives.
 * It enables better intro title, text, and image settings.
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category   WPS\WP\Genesis
 * @package    WPS\WP\Genesis
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://wpsmith.net/
 * @since      0.0.1
 */

namespace WPS\WP\Genesis;

use WPS\Core\Singleton;

if ( ! class_exists( __NAMESPACE__ . '\ArchiveSettings' ) ) {
	/**
	 * Class ArchiveSettings
	 * @package WPS\WP\Genesis
	 */
	class ArchiveSettings extends Singleton {

		/**
		 * @var [string]int Stores taxonomy names.
		 */
		protected $taxonomies = [];

		/**
		 * @var [string]TaxArchiveSettings Stores the new taxonomy archive settings.
		 */
		protected $taxonomy_archives = [];

		/**
		 * @var [string]int Stores post type names.
		 */
		protected $post_types = [];

		/**
		 * @var [string]CPTArchiveSettings Stores the new custom post type archive settings.
		 */
		protected $post_type_archives = [];


		protected function __construct() {
			if ( did_action( 'admin_init' ) || did_action( 'admin_menu' ) ) {
				$message = 'Instantiated too late.';
				_doing_it_wrong( __CLASS__, $message, $GLOBALS['wp_version'] );

				return new \WP_Error( 'too-late', $message );
			}

			add_action( 'wp_loaded', [ $this, 'cpt_archive_options' ], PHP_INT_MAX );
			add_action( 'admin_init', [ $this, 'genesis_add_taxonomy_archive_options' ], 999 );
		}

		/**
		 * Gets the taxonomy name.
		 *
		 * @param string|\WP_Post_Type $post_type Post type being checked.
		 *
		 * @return string
		 */
		protected function get_post_type_name( $post_type ) {
			if ( is_a( $post_type, 'WP_Post_Type' ) ) {
				return $post_type->name;
			}

			return $post_type;
		}

		/**
		 * Gets the taxonomy name.
		 *
		 * @param string|\WP_Taxonomy $taxonomy Post type being checked.
		 *
		 * @return string
		 */
		protected function get_taxonomy_name( $taxonomy ) {
			if ( is_a( $taxonomy, 'WP_Taxonomy' ) ) {
				return $taxonomy->name;
			}

			return $taxonomy;
		}

		/**
		 * Registers a post type to support.
		 *
		 * @param string|\WP_Post_Type $post_type Post type being registered.
		 */
		public function register_post_type( $post_type ) {
			$this->post_types[ $this->get_post_type_name( $post_type ) ] = 1;
		}

		/**
		 * Registers a taxonomy to support.
		 *
		 * @param string|\WP_Taxonomy $taxonomy Taxonomy being registered.
		 */
		public function register_taxonomy( $taxonomy ) {
			$this->taxonomies[ $this->get_taxonomy_name( $taxonomy ) ] = 1;
		}

		/**
		 * Unregisters a post type to support.
		 *
		 * @param string|\WP_Post_Type $post_type Post type being unregistered.
		 */
		public function unregister_post_type( $post_type ) {
			$this->post_types[ $this->get_post_type_name( $post_type ) ] = 0;
		}

		/**
		 * Unegisters a taxonomy to support.
		 *
		 * @param string|\WP_Taxonomy $taxonomy Taxonomy being registered.
		 */
		public function unregister_taxonomy( $taxonomy ) {
			$this->taxonomies[ $this->get_taxonomy_name( $taxonomy ) ] = 0;
		}

		/**
		 * Sets up the CPT Archive Settings Pages.
		 */
		public function cpt_archive_options() {
			remove_action( 'admin_menu', 'genesis_add_cpt_archive_page', 5 );
			add_action( 'admin_menu', [ $this, 'genesis_add_cpt_archive_options' ], 5 );
		}

		/**
		 * Determines whether a specific taxonomy is supported by these Archive Settings.
		 *
		 * @param string|\WP_Taxonomy $taxonomy Post type being checked.
		 *
		 * @return bool
		 */
		protected function is_taxonomy_supported( $taxonomy ) {
			$taxonomy = $this->get_taxonomy_name( $taxonomy );

			return (
				count( $this->taxonomies ) > 0 &&
				isset( $this->taxonomies[ $taxonomy ] ) &&
				$this->taxonomies[ $taxonomy ]
			);
		}

		/**
		 * Determines whether a specific post type is supported by these Archive Settings.
		 *
		 * @param string|\WP_Post_Type $post_type Post type being checked.
		 *
		 * @return bool
		 */
		protected function is_post_type_supported( $post_type ) {
			$post_type = $this->get_post_type_name( $post_type );

			return (
				count( $this->post_types ) > 0 &&
				isset( $this->post_types[ $post_type ] ) &&
				$this->post_types[ $post_type ]
			);
		}

		/**
		 * Add archive settings page to relevant custom post type registrations.
		 *
		 * An instance of `Genesis_Admin_CPT_Archive_Settings` or `CPTArchiveSettings` is instantiated for each relevant CPT,
		 * assigned to an individual global variable.
		 */
		public function genesis_add_cpt_archive_options() {
			if ( empty( $this->post_types ) && function_exists( 'genesis_add_cpt_archive_page' ) ) {
				return genesis_add_cpt_archive_page();
			}

			$this->default_genesis_cpt_archive_options();
			$this->new_genesis_cpt_archive_options();
		}

		/**
		 * Add archive settings page to relevant custom post type registrations.
		 *
		 * An instance of `Genesis_Admin_CPT_Archive_Settings` is instantiated for each relevant CPT, assigned to an individual
		 * global variable.
		 */
		public function default_genesis_cpt_archive_options() {
			$post_types = genesis_get_cpt_archive_types();

			foreach ( $post_types as $post_type ) {
				if (
					! $this->is_post_type_supported( $post_type->name ) &&
					genesis_has_post_type_archive_support( $post_type->name ) ) {

					$admin_object_name = '_genesis_admin_cpt_archives_' . $post_type->name;
					// phpcs:ignore PHPCompatibility.Variables.ForbiddenGlobalVariableVariable.NonBareVariableFound -- Programatically generated name of global
					global ${$admin_object_name};
					// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Programatically generated name of global
					${$admin_object_name} = new \Genesis_Admin_CPT_Archive_Settings( $post_type );

				}
			}
		}

		/**
		 * Add archive settings page to relevant custom post type registrations.
		 *
		 * An instance of `CPTArchiveSettings` is instantiated for each relevant CPT, assigned to an individual
		 * global variable.
		 *
		 */
		public function new_genesis_cpt_archive_options() {

			foreach ( $this->post_types as $post_type => $enabled ) {
				$post_type = get_post_type_object( $post_type );

				$admin_object_name = '_genesis_admin_cpt_archives_' . $post_type->name;
				// phpcs:ignore PHPCompatibility.Variables.ForbiddenGlobalVariableVariable.NonBareVariableFound -- Programatically generated name of global
				global ${$admin_object_name};
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Programatically generated name of global
				${$admin_object_name} = new CPTArchiveSettings( $post_type );

				$this->post_type_archives[ $post_type->name ] = ${$admin_object_name};
			}

		}

		/**
		 * Replace genesis_taxonomy_archive_options with our own.
		 */
		public function genesis_add_taxonomy_archive_options() {

			if ( empty( $this->taxonomies ) ) {
				return;
			}

			foreach ( $this->taxonomies as $taxonomy => $enabled ) {
				if ( $enabled ) {
					remove_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_archive_options' );
					add_action( "{$taxonomy}_edit_form", [ $this, 'genesis_taxonomy_archive_options' ], 10, 2 );
				}
			}

		}

		/**
		 * Echo headline, headline image, and introduction fields on the taxonomy term edit form.
		 *
		 * If populated, the values saved in these fields may display on taxonomy archives.
		 *
		 * @param \stdClass $tag Term object.
		 * @param string $taxonomy Name of the taxonomy.
		 */
		public function genesis_taxonomy_archive_options( $tag, $taxonomy ) {

			$admin_object_name = '_genesis_admin_tax_archives_' . $taxonomy;
			// phpcs:ignore PHPCompatibility.Variables.ForbiddenGlobalVariableVariable.NonBareVariableFound -- Programatically generated name of global
			global ${$admin_object_name};
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Programatically generated name of global
			${$admin_object_name} = new TaxArchiveSettings();
			${$admin_object_name}->show_meta_box( 'genesis-term-meta-settings', $tag );
			$this->taxonomy_archives[ $taxonomy ] = ${$admin_object_name};

		}

	}
}