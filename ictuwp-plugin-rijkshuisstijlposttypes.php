<?php

/**
 * @link              https://wbvb.nl
 * @package           ictuwp-plugin-rijkshuisstijlposttypes
 *
 * @wordpress-plugin
 * Plugin Name:       ICTU / WP Register post types and taxonomies
 * Plugin URI:        https://github.com/ICTU/Digitale-Overheid---WordPress-Custom-Post-Types-and-Taxonomies
 * Description:       Plugin for digitaleoverheid.nl to register custom post types and custom taxonomies
 * Version:           2.0.1
 * Author:            Paul van Buuren
 * Author URI:        https://wbvb.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rhswp-posttypes
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
  define( 'RHSWP_CT_DOSSIER',               'dossiers' );   // slug for custom taxonomy 'dossier'
}

if ( ! defined( 'RHSWP_CT_DIGIBETER' ) ) {
  define( 'RHSWP_CT_DIGIBETER',             'digitaleagenda' );   // custom taxonomy for digitale agenda
}

if ( ! defined( 'RHSWP_CPT_DOCUMENT' ) ) {
  define( 'RHSWP_CPT_DOCUMENT',             'document' );   // slug for custom taxonomy 'document'
}

if ( ! defined( 'RHSWP_CPT_SLIDER' ) ) {
  define( 'RHSWP_CPT_SLIDER',               'slidertje' );  // slug for custom taxonomy 'dossier'
}

//========================================================================================================
// constants for rewrite rules

if ( ! defined( 'RHSWP_DOSSIERPOSTCONTEXT' ) ) {
  define( 'RHSWP_DOSSIERPOSTCONTEXT',         'dossierpostcontext' );
}

if ( ! defined( 'RHSWP_DOSSIEREVENTCONTEXT' ) ) {
  define( 'RHSWP_DOSSIEREVENTCONTEXT',        'dossiereventcontext' );
}

if ( ! defined( 'RHSWP_DOSSIERDOCUMENTCONTEXT' ) ) {
  define( 'RHSWP_DOSSIERDOCUMENTCONTEXT',     'dossierdocumentcontext' );
}

//========================================================================================================


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.1
 */


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
      add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
      add_action( 'init', array( $this, 'rhswp_dossiercontext_add_rewrite_rules' ) );

    }
  
    /** ----------------------------------------------------------------------------------------------------
     * Initialise translations
     */
    public function load_plugin_textdomain() {

      load_plugin_textdomain( "rhswp-posttypes", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    /** ----------------------------------------------------------------------------------------------------
     * Do actually register the post types we need
     */
    public function register_post_type() {
      
      // ---------------------------------------------------------------------------------------------------
      // dossier custom taxonomy
    	$labels = array(
    		"name"                  => _x( 'Topics', 'Dossier', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Topic', 'Dossier', 'rhswp-posttypes' ),
    		);
    
    	$labels = array(
    		"name"                  => _x( 'Topics', 'Dossier', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Topic', 'Dossier', 'rhswp-posttypes' ),
    		"menu_name"             => _x( 'Topics', 'Dossier', 'rhswp-posttypes' ),
    		"all_items"             => _x( 'All topics', 'Dossier', 'rhswp-posttypes' ),
    		"add_new"               => _x( 'Add new topic', 'Dossier', 'rhswp-posttypes' ),
    		"add_new_item"          => _x( 'Add new topic', 'Dossier', 'rhswp-posttypes' ),
    		"edit_item"             => _x( 'Edit topic', 'Dossier', 'rhswp-posttypes' ),
    		"new_item"              => _x( 'New topic', 'Dossier', 'rhswp-posttypes' ),
    		"view_item"             => _x( 'View topic', 'Dossier', 'rhswp-posttypes' ),
    		"search_items"          => _x( 'Search topic', 'Dossier', 'rhswp-posttypes' ),
    		"not_found"             => _x( 'No topic found', 'Dossier', 'rhswp-posttypes' ),
    		"not_found_in_trash"    => _x( 'No topic found in trash', 'Dossier', 'rhswp-posttypes' ),
    		"featured_image"        => __( 'Featured image', 'rhswp-posttypes' ),
    		"archives"              => __( 'Archives', 'rhswp-posttypes' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'rhswp-posttypes' ),
    		);
  
    	$args = array(
    		"label"                 => _x( 'Topics', 'Dossiers label', 'rhswp-posttypes' ),
    		"labels"              => $labels,
    		"public"              => true,
    		"hierarchical"        => true,
    		"label"                  => _x( 'Topics', 'Dossier', 'rhswp-posttypes' ),
    		"show_ui"             => true,
    		"show_in_menu"        => true,
    		"show_in_nav_menus"   => true,
    		"query_var"           => true,
    		"rewrite"             => array( 'slug' => RHSWP_CT_DOSSIER, 'with_front' => true, ),
    		"show_admin_column"   => false,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"show_in_quick_edit"  => false,
    	);
    	register_taxonomy( RHSWP_CT_DOSSIER, array( "post", "page", "links", 'event', "document" ), $args );

      // ---------------------------------------------------------------------------------------------------
      // digitbeter kleuren custom taxonomy
    	$labels = array(
    		"name"                  => _x( 'Onderdelen NL Digibeter', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Onderdeel NL Digibeter', 'digibeterkleuren', 'rhswp-posttypes' )
    		);
    
    	$labels = array(
    		"name"                  => _x( 'Digibeter-kleuren', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Digibeter-kleur', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"menu_name"             => _x( 'Digibeter-kleuren', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"all_items"             => _x( 'Alle kleuren', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"add_new"               => _x( 'Nieuw onderdeel toevoegen', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"add_new_item"          => _x( 'Voeg nieuw onderdeel toe', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"edit_item"             => _x( 'Bewerk onderdeel', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"new_item"              => _x( 'Nieuw onderdeel', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"view_item"             => _x( 'Bekijk onderdeel', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"search_items"          => _x( 'Zoek onderdeel', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"not_found"             => _x( 'Geen onderdelen gevonden', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"not_found_in_trash"    => _x( 'Geen onderdelen gevonden in de prullenbak', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"featured_image"        => __( 'Featured image', 'rhswp-posttypes' ),
    		"archives"              => __( 'Archives', 'rhswp-posttypes' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'rhswp-posttypes' ),
    		);

    	$args = array(
    		"label"                 => _x( 'Onderdelen NL Digibeter', 'Digibeter label', 'rhswp-posttypes' ),
    		"labels"              => $labels,
    		"public"              => true,
    		"hierarchical"        => true,
    		"label"                  => _x( 'Onderdelen NL Digibeter', 'digibeterkleuren', 'rhswp-posttypes' ),
    		"show_ui"             => true,
    		"show_in_menu"        => true,
    		"show_in_nav_menus"   => true,
    		"query_var"           => true,
    		"rewrite"             => array( 'slug' => RHSWP_CT_DIGIBETER, 'with_front' => true, ),
    		"show_admin_column"   => false,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"show_in_quick_edit"  => false,
    	);
    	register_taxonomy( RHSWP_CT_DIGIBETER, array( "page" ), $args );

      // ---------------------------------------------------------------------------------------------------
      // documenten custom post type
    	$labels = array(
    		"name"                  => _x( 'Documents', 'document type', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Document', 'document type', 'rhswp-posttypes' ),
    		"menu_name"             => _x( 'Documents', 'document type', 'rhswp-posttypes' ),
    		"all_items"             => _x( 'All documents', 'document type', 'rhswp-posttypes' ),
    		"add_new"               => _x( 'Add new document', 'document type', 'rhswp-posttypes' ),
    		"add_new_item"          => _x( 'Add new document', 'document type', 'rhswp-posttypes' ),
    		"edit_item"             => _x( 'Edit document', 'document type', 'rhswp-posttypes' ),
    		"new_item"              => _x( 'New document', 'document type', 'rhswp-posttypes' ),
    		"view_item"             => _x( 'View document', 'document type', 'rhswp-posttypes' ),
    		"search_items"          => _x( 'Search document', 'document type', 'rhswp-posttypes' ),
    		"not_found"             => _x( 'No documents found', 'document type', 'rhswp-posttypes' ),
    		"not_found_in_trash"    => _x( 'No documents found in trash', 'document type', 'rhswp-posttypes' ),
    		"featured_image"        => __( 'Featured image', 'rhswp-posttypes' ),
    		"archives"              => __( 'Archives', 'rhswp-posttypes' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'rhswp-posttypes' ),
    		);
    
    	$args = array(
    		"label"                 => _x( 'Documents', 'Documents label', 'rhswp-posttypes' ),
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
    		"name"                  => _x( 'Carrousels', 'caroussel', 'rhswp-posttypes' ),
    		"singular_name"         => _x( 'Carrousel', 'caroussel', 'rhswp-posttypes' ),
    		"menu_name"             => _x( 'Carrousels', 'caroussel', 'rhswp-posttypes' ),
    		"all_items"             => _x( 'Alle carrousels', 'caroussel', 'rhswp-posttypes' ),
    		"add_new"               => _x( 'Nieuwe carrousel toevoegen', 'caroussel', 'rhswp-posttypes' ),
    		"add_new_item"          => _x( 'Voeg nieuwe carrousel toe', 'caroussel', 'rhswp-posttypes' ),
    		"edit_item"             => _x( 'Bewerk carrousel', 'caroussel', 'rhswp-posttypes' ),
    		"new_item"              => _x( 'Nieuwe carrousel', 'caroussel', 'rhswp-posttypes' ),
    		"view_item"             => _x( 'Bekijk carrousel', 'caroussel', 'rhswp-posttypes' ),
    		"search_items"          => _x( 'Zoek carrousel', 'caroussel', 'rhswp-posttypes' ),
    		"not_found"             => _x( 'Geen carrousels gevonden', 'caroussel', 'rhswp-posttypes' ),
    		"not_found_in_trash"    => _x( 'Geen carrousels gevonden in de prullenbak', 'caroussel', 'rhswp-posttypes' ),
    		"featured_image"        => __( 'Featured image', 'rhswp-posttypes' ),
    		"archives"              => __( 'Archives', 'rhswp-posttypes' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'rhswp-posttypes' ),
    		);
    
    	$args = array(
    		"label"                 => _x( 'Carrousels', 'Carrousels label', 'rhswp-posttypes' ),
    		"labels"                => $labels,
    		"description"           => _x( "Foto\'s en links. Toe te voegen aan pagina\'s en taxonomieen op Digitale Overheid", 'Carrousels description', 'rhswp-posttypes' ), 
    		"public"                => true,
    		"publicly_queryable"    => false,
    		"show_ui"               => true,
    		"show_in_rest"          => false,
    		"rest_base"             => "",
    		"has_archive"           => false,
    		"show_in_menu"          => true,
    		"exclude_from_search"   => false,
    		"capability_type"       => "post",
    		"map_meta_cap"          => true,
    		"hierarchical"          => false,
    		"rewrite"               => array( "slug" => "carrousel", "with_front" => false ),
    		"query_var"             => false,
    		"supports"              => array( "title", "excerpt", "revisions" ),					);
    	register_post_type( RHSWP_CPT_SLIDER, $args );
  
    
      // ---------------------------------------------------------------------------------------------------
      // clean up after ourselves
    	flush_rewrite_rules();
  
    }


    /** ----------------------------------------------------------------------------------------------------
     * Add rewrite rules
     */
    public function rhswp_dossiercontext_add_rewrite_rules() {
    
      // rewrite rules for posts in dossier context
      add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIERPOSTCONTEXT . '/)(.+?)/?$', 'index.php?name=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top');
      add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIERPOSTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top');
    
      // rewrite rules for documents in dossier context
      add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIERDOCUMENTCONTEXT . '/)(.+?)/?$', 'index.php?document=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top');
      add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIERDOCUMENTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top');
    
      // rewrite rules for events in dossier context
      add_rewrite_rule( '(.+?)(/' . RHSWP_DOSSIEREVENTCONTEXT . '/)(.+?)/?$', 'index.php?event=$matches[3]&' . RHSWP_DOSSIERPOSTCONTEXT . '=$matches[1]', 'top');
      add_rewrite_rule( '(.+?)/' . RHSWP_DOSSIEREVENTCONTEXT . '/?$', 'index.php?pagename=$matches[1]', 'top');
      
      // posts overview with paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
    
      // posts overview without paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
      
      // events overview with paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
    
      // events overview without paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
    
      // documents overview with paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '/page/([0-9]+)/?$', 'index.php?paged=$matches[2]&pagename=' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
    
      // documents overview without paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]', 'top');
    
    
      // posts overview for category with paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/(.+?)/page/([0-9]+)/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]&paged=$matches[3]', 'top');
    
      // single post in context of dossier and category
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/([^/]*)/([^/]*)/?$', 'index.php?' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]&name=$matches[3]', 'top');
    
      // posts overview for category without paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/([^/]*)/?$', 'index.php?pagename=' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '&' . RHSWP_CT_DOSSIER . '=$matches[1]&category_slug=$matches[2]', 'top');
    
      // posts overview without category without paging
      add_rewrite_rule( RHSWP_CT_DOSSIER . '/(.+?)/' . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/([^/]*)/?$', 'index.php?' . RHSWP_CT_DOSSIER . '=$matches[1]&name=$matches[2]', 'top');
    
      if ( function_exists( 'get_field' ) ) {
        if( get_field( 'global_search_page', 'option') ) {
          
          $zoekpagina = get_field( 'global_search_page', 'option');
      
          // rewrite rules for events in dossier context
          add_rewrite_rule( '?(s=)(.+?)?$', 'index.php?page_id=' . $zoekpagina->ID . '&searchwpquery=$matches[2]', 'top');
          
        }  
      }  
    }
    //** ---------------------------------------------------------------------------------------------------
  
  
  }

endif;

//========================================================================================================

