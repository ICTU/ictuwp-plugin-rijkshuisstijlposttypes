<?php

/**
 * @link              https://wbvb.nl
 * @package           ictuwp-plugin-ictuwp-plugin-rijkshuisstijlposttypes
 *
 * @wordpress-plugin
 * Plugin Name:       ICTU / WP / DO Register post types and taxonomies
 * Plugin URI:        https://github.com/ICTU/Digitale-Overheid---WordPress-Custom-Post-Types-and-Taxonomies
 * Description:       Plugin for digitaleoverheid.nl to register custom post types and custom taxonomies
 * Version:           3.0.2
 * Author:            Paul van Buuren
 * Author URI:        https://wbvb.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ictuwp-plugin-rijkshuisstijlposttypes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//========================================================================================================

add_action( 'plugins_loaded', array( 'RHSWP_Register_taxonomies', 'init' ), 10 );

//========================================================================================================

if ( ! defined( 'RHSWP_CT_DOSSIER' ) ) {
	define( 'RHSWP_CT_DOSSIER', 'dossiers' );   // slug for custom taxonomy 'dossier'
}

if ( ! defined( 'RHSWP_CT_DIGIBETER' ) ) {
	define( 'RHSWP_CT_DIGIBETER', 'beleidsterreinen' );   // custom taxonomy for digitale agenda
}

if ( ! defined( 'RHSWP_CPT_DOCUMENT' ) ) {
	define( 'RHSWP_CPT_DOCUMENT', 'document' );   // slug for custom taxonomy 'document'
}

if ( ! defined( 'RHSWP_CPT_SLIDER' ) ) {
	define( 'RHSWP_CPT_SLIDER', 'slidertje' );  // slug for custom taxonomy 'dossier'
}

// @since 3.0.2
if ( ! defined( 'RHSWP_CPT_VERWIJZING' ) ) {
	define( 'RHSWP_CPT_VERWIJZING', 'externeverwijzing' );
}

//========================================================================================================
// constants for rewrite rules

if ( ! defined( 'RHSWP_DOSSIERPOSTCONTEXT' ) ) {
	define( 'RHSWP_DOSSIERPOSTCONTEXT', 'dossierpostcontext' );
}

if ( ! defined( 'RHSWP_DOSSIEREVENTCONTEXT' ) ) {
	define( 'RHSWP_DOSSIEREVENTCONTEXT', 'dossiereventcontext' );
}

if ( ! defined( 'RHSWP_DOSSIERDOCUMENTCONTEXT' ) ) {
	define( 'RHSWP_DOSSIERDOCUMENTCONTEXT', 'dossierdocumentcontext' );
}

//========================================================================================================


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.2
 */

//    	die('Translate folder hiero: ' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

if ( ! class_exists( 'RHSWP_Register_taxonomies' ) ) :

	class RHSWP_Register_taxonomies {

		/**
		 * @var Rijksvideo
		 */
		public $rhswpregistration = null;

		/** ----------------------------------------------------------------------------------------------------
		 * Init
		 */
		public static function init() {

			$rhswpregistration = new self();

		}

		/** ----------------------------------------------------------------------------------------------------
		 * Constructor
		 */
		public function __construct() {

			$this->setup_actions();

		}

		/** ----------------------------------------------------------------------------------------------------
		 * Hook this plugins functions into WordPress
		 */
		private function setup_actions() {

			add_action( 'init', array( $this, 'register_post_type' ) );
//		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'rhswp_dossiercontext_add_rewrite_rules' ) );

		}

		/** ----------------------------------------------------------------------------------------------------
		 * Initialise translations
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain( 'ictuwp-plugin-rijkshuisstijlposttypes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		}

		/** ----------------------------------------------------------------------------------------------------
		 * Do actually register the post types we need
		 */
		public function register_post_type() {

			// ---------------------------------------------------------------------------------------------------
			// dossier custom taxonomy
			$labels = array(
				"name"          => _x( 'Topics', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name" => _x( 'Topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$labels = array(
				"name"                  => _x( 'Topics', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name"         => _x( 'Topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"menu_name"             => _x( 'Topics', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"all_items"             => _x( 'All topics', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new"               => _x( 'Add new topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new_item"          => _x( 'Add new topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"edit_item"             => _x( 'Edit topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"new_item"              => _x( 'New topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"view_item"             => _x( 'View topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"search_items"          => _x( 'Search topic', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found"             => _x( 'No topic found', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found_in_trash"    => _x( 'No topic found in trash', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"featured_image"        => __( 'Featured image', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"archives"              => __( 'Archives', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"uploaded_to_this_item" => __( 'Uploaded media', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$args = array(
				"label"              => _x( 'Topics', 'Dossiers label', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"labels"             => $labels,
				"public"             => true,
				"hierarchical"       => true,
				"label"              => _x( 'Topics', 'Dossier', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"show_ui"            => true,
				"show_in_menu"       => true,
				"show_in_nav_menus"  => true,
				"query_var"          => true,
				"rewrite"            => array( 'slug' => RHSWP_CT_DOSSIER, 'with_front' => true, ),
				"show_admin_column"  => false,
				"show_in_rest"       => false,
				"rest_base"          => "",
				"show_in_quick_edit" => false,
			);
			register_taxonomy( RHSWP_CT_DOSSIER, array( "post", "page", "links", 'event', "document" ), $args );

			// ---------------------------------------------------------------------------------------------------
			// digitbeter kleuren custom taxonomy
			$labels = array(
				"name"          => _x( 'Onderdelen NL Digibeter', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name" => _x( 'Onderdeel NL Digibeter', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' )
			);

			$labels = array(
				"name"                  => _x( 'Digibeter-kleuren', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name"         => _x( 'Digibeter-kleur', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"menu_name"             => _x( 'Digibeter-kleuren', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"all_items"             => _x( 'Alle kleuren', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new"               => _x( 'Nieuw onderdeel toevoegen', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new_item"          => _x( 'Voeg nieuw onderdeel toe', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"edit_item"             => _x( 'Bewerk onderdeel', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"new_item"              => _x( 'Nieuw onderdeel', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"view_item"             => _x( 'Bekijk onderdeel', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"search_items"          => _x( 'Zoek onderdeel', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found"             => _x( 'Geen onderdelen gevonden', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found_in_trash"    => _x( 'Geen onderdelen gevonden in de prullenbak', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"featured_image"        => __( 'Featured image', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"archives"              => __( 'Archives', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"uploaded_to_this_item" => __( 'Uploaded media', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$args = array(
				"label"              => _x( 'Onderdelen NL Digibeter', 'Digibeter label', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"labels"             => $labels,
				"public"             => true,
				"hierarchical"       => true,
				"label"              => _x( 'Onderdelen NL Digibeter', 'digibeterkleuren', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"show_ui"            => true,
				"show_in_menu"       => true,
				"show_in_nav_menus"  => true,
				"query_var"          => true,
				"rewrite"            => array( 'slug' => RHSWP_CT_DIGIBETER, 'with_front' => true, ),
				"show_admin_column"  => false,
				"show_in_rest"       => false,
				"rest_base"          => "",
				"show_in_quick_edit" => false,
			);
			register_taxonomy( RHSWP_CT_DIGIBETER, array( "page" ), $args );

			// ---------------------------------------------------------------------------------------------------
			// documenten custom post type
			$labels = array(
				"name"                  => _x( 'Documents', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name"         => _x( 'Document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"menu_name"             => _x( 'Documents', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"all_items"             => _x( 'All documents', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new"               => _x( 'Add new document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new_item"          => _x( 'Add new document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"edit_item"             => _x( 'Edit document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"new_item"              => _x( 'New document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"view_item"             => _x( 'View document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"search_items"          => _x( 'Search document', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found"             => _x( 'No documents found', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found_in_trash"    => _x( 'No documents found in trash', 'document type', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"featured_image"        => __( 'Featured image', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"archives"              => __( 'Archives', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"uploaded_to_this_item" => __( 'Uploaded media', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$args = array(
				"label"               => _x( 'Documents', 'Documents label', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"labels"              => $labels,
				"description"         => "",
				"public"              => true,
				"publicly_queryable"  => true,
				"show_ui"             => true,
				"show_in_rest"        => false,
				"rest_base"           => "",
				"has_archive"         => true,
				"show_in_menu"        => true,
				"exclude_from_search" => false,
				"capability_type"     => "post",
				"map_meta_cap"        => true,
				"hierarchical"        => false,
				"rewrite"             => array( "slug" => RHSWP_CPT_DOCUMENT, "with_front" => true ),
				"query_var"           => true,
				"supports"            => array( "title", "editor", "thumbnail", "excerpt" ),
				"taxonomies"          => array( "dossiers" ),
			);
			register_post_type( RHSWP_CPT_DOCUMENT, $args );

			// ---------------------------------------------------------------------------------------------------
			// Carrousels custom post type
			$labels = array(
				"name"                  => _x( 'Carrousels', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name"         => _x( 'Carrousel', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"menu_name"             => _x( 'Carrousels', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"all_items"             => _x( 'Alle carrousels', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new"               => _x( 'Nieuwe carrousel toevoegen', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new_item"          => _x( 'Voeg nieuwe carrousel toe', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"edit_item"             => _x( 'Bewerk carrousel', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"new_item"              => _x( 'Nieuwe carrousel', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"view_item"             => _x( 'Bekijk carrousel', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"search_items"          => _x( 'Zoek carrousel', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found"             => _x( 'Geen carrousels gevonden', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found_in_trash"    => _x( 'Geen carrousels gevonden in de prullenbak', 'caroussel', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"featured_image"        => __( 'Featured image', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"archives"              => __( 'Archives', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"uploaded_to_this_item" => __( 'Uploaded media', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$args = array(
				"label"               => _x( 'Carrousels', 'Carrousels label', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"labels"              => $labels,
				"description"         => _x( "Foto\'s en links. Toe te voegen aan pagina\'s en taxonomieen op Digitale Overheid", 'Carrousels description', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"public"              => true,
				"publicly_queryable"  => false,
				"show_ui"             => true,
				"show_in_rest"        => false,
				"rest_base"           => "",
				"has_archive"         => false,
				"show_in_menu"        => true,
				"exclude_from_search" => false,
				"capability_type"     => "post",
				"map_meta_cap"        => true,
				"hierarchical"        => false,
				"rewrite"             => array( "slug" => "carrousel", "with_front" => false ),
				"query_var"           => false,
				"supports"            => array( "title", "excerpt", "revisions" ),
			);
			register_post_type( RHSWP_CPT_SLIDER, $args );


			// ---------------------------------------------------------------------------------------------------
			// Externe verwijzing
			// @since 3.0.2
			$labels = array(
				"name"                  => _x( 'Verwijzingen', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"singular_name"         => _x( 'Verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"menu_name"             => _x( 'Verwijzingen', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"all_items"             => _x( 'Alle verwijzingen', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new"               => _x( 'Nieuwe verwijzing toevoegen', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"add_new_item"          => _x( 'Nieuwe verwijzing toevoegen', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"edit_item"             => _x( 'Bewerk verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"new_item"              => _x( 'Nieuwe verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"view_item"             => _x( 'Bekijk verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"search_items"          => _x( 'Zoek verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found"             => _x( 'Geen verwijzingen gevonden', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"not_found_in_trash"    => _x( 'Geen verwijzingen gevonden', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"featured_image"        => __( 'Featured image', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"archives"              => __( 'Archives', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"uploaded_to_this_item" => __( 'Uploaded media', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
			);

			$args = array(
				"label"               => _x( 'Verwijzing', 'CPT verwijzing', 'ictuwp-plugin-rijkshuisstijlposttypes' ),
				"labels"              => $labels,
				"description"         => "",
				"public"              => false,
				"publicly_queryable"  => false,
				"show_ui"             => true,
				"show_in_rest"        => false,
				"rest_base"           => "",
				"has_archive"         => false,
				"show_in_menu"        => true,
				"exclude_from_search" => false,
				"capability_type"     => "post",
				"map_meta_cap"        => true,
				"hierarchical"        => false,
				"rewrite"             => array( "slug" => RHSWP_CPT_VERWIJZING, "with_front" => true ),
				"query_var"           => true,
				"supports"            => array( "title", "thumbnail" ),
			);

			register_post_type( RHSWP_CPT_VERWIJZING, $args );

			if ( function_exists( 'acf_add_local_field_group' ) ):

				acf_add_local_field_group( array(
					'key'                   => 'group_5ebc14c5d080f',
					'title'                 => 'URL en citaat bij deze verwijzing',
					'fields'                => array(
						array(
							'key'               => 'field_5ebd4763dbe21',
							'label'             => 'Citaat of verwijzing',
							'name'              => 'citaat_of_verwijzing',
							'type'              => 'radio',
							'instructions'      => 'Zowel bij een verwijzing als citaat voeg je een foto toe. Bij beide voeg je ook een link en linktekst toe.
			Een verwijzing bevat een tekst van meerdere regels.
			Een citaat daarentegen is kort en heeft een auteur.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'citaat_of_verwijzing_verwijzing' => 'Verwijzing',
								'citaat_of_verwijzing_citaat'     => 'Citaat',
							),
							'allow_null'        => 0,
							'other_choice'      => 0,
							'default_value'     => 'citaat_of_verwijzing_verwijzing',
							'layout'            => 'vertical',
							'return_format'     => 'value',
							'save_other_choice' => 0,
						),
						array(
							'key'               => 'field_5ebd4836470c7',
							'label'             => 'Citaat en auteur',
							'name'              => 'citaat_en_auteur',
							'type'              => 'group',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5ebd4763dbe21',
										'operator' => '==',
										'value'    => 'citaat_of_verwijzing_citaat',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'row',
							'sub_fields'        => array(
								array(
									'key'               => 'field_5ebc14df0d0c8',
									'label'             => 'Citaat',
									'name'              => 'verwijzing_citaat',
									'type'              => 'textarea',
									'instructions'      => '',
									'required'          => 1,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'maxlength'         => 400,
									'rows'              => 2,
									'new_lines'         => '',
								),
								array(
									'key'               => 'field_5ebd48a1a5185',
									'label'             => 'Auteur',
									'name'              => 'verwijzing_citaat_auteur',
									'type'              => 'text',
									'instructions'      => '',
									'required'          => 1,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								),
							),
						),
						array(
							'key'               => 'field_5ebc176381bfd',
							'label'             => 'Beschrijving',
							'name'              => 'verwijzing_beschrijving',
							'type'              => 'textarea',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5ebd4763dbe21',
										'operator' => '==',
										'value'    => 'citaat_of_verwijzing_verwijzing',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'rows'              => '',
							'new_lines'         => '',
						),
						array(
							'key'               => 'field_5ebd457d97efa',
							'label'             => 'Link',
							'name'              => 'verwijzing_url',
							'type'              => 'link',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => RHSWP_CPT_VERWIJZING,
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				) );

			endif;

			// ---------------------------------------------------------------------------------------------------
			// clean up after ourselves
			flush_rewrite_rules();

		}


		/** ----------------------------------------------------------------------------------------------------
		 * Add rewrite rules
		 */
		public function rhswp_dossiercontext_add_rewrite_rules() {

			// rewrite rules for posts in dossier context
			add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIERPOSTCONTEXT . '/)(.+?)/?$', 'index.php?name=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top' );
			add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIERPOSTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top' );

			// rewrite rules for documents in dossier context
			add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIERDOCUMENTCONTEXT . '/)(.+?)/?$', 'index.php?document=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top' );
			add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIERDOCUMENTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top' );

			// rewrite rules for events in dossier context
			add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIEREVENTCONTEXT . '/)(.+?)/?$', 'index.php?event=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top' );
			add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIEREVENTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top' );

			// posts overview with paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );

			// posts overview without paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );

			// events overview with paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );

			// events overview without paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );

			// documents overview with paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );

			// documents overview without paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top' );


			// posts overview for category with paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/(.+?)/page/([0-9]+)/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]&paged=$matches[3]', 'top' );

			// single post in context of dossier and category
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/([^/]*)/([^/]*)/?$', 'index.php?' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]&name=$matches[3]', 'top' );

			// posts overview for category without paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/([^/]*)/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]', 'top' );

			// posts overview without category without paging
			add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/([^/]*)/?$', 'index.php?' . RHSWP_CT_DOSSIER . '=$matches[1]&name=$matches[2]', 'top' );

			if ( function_exists( 'get_field' ) ) {
				if ( get_field( 'global_search_page', 'option' ) ) {

					$zoekpagina = get_field( 'global_search_page', 'option' );

					// rewrite rules for events in dossier context
					add_rewrite_rule( '?(s=)(.+?)?$', 'index.php?page_id=' . $zoekpagina->ID . '&searchwpquery=$matches[2]', 'top' );

				}
			}
		}
		//** ---------------------------------------------------------------------------------------------------


	}

endif;

//========================================================================================================

