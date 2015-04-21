<?php
/**
 * Plugin Name: WordSlides
 * Plugin URI: http://anantshri.info/reveal.js/presentation
 * Description: Wordpress based Slides / Presentation maker using markdown forma
 * Version: 0.1
 * Author: Anant Shrivastava
 * Author URI: http://anantshri.info
 * License: GPL2
 */
/*
TODO: 
1. Presentations pages (show css style and title in a square box)
1. External Markdown resource location option
1. Options for plugin
    - Notes
    - Zoom 
    - controls: true,
    - slideNumber: true,
    - progress: true,
    - history: true,
    - center: false,
    - help: true,
    - transition: 'slide'
1. remote control configuration
1. option to print PDF
1. Notes need to be placed properly till then remove notes option
*/
$post_type_name="presentation";
add_action( 'init', 'register_cpt_presentation' );
add_filter( 'jetpack_enable_open_graph', '__return_false', 99 );

function register_cpt_presentation() {

    $labels = array( 
        'name' => _x( 'Presentation', 'presentation' ),
        'singular_name' => _x( 'Presentation', 'presentation' ),
        'add_new' => _x( 'Add New', 'presentation' ),
        'add_new_item' => _x( 'Add New Presentation', 'presentation' ),
        'edit_item' => _x( 'Edit Presentation', 'presentation' ),
        'new_item' => _x( 'New Presentation', 'presentation' ),
        'view_item' => _x( 'View Presentation', 'presentation' ),
        'search_items' => _x( 'Search Presentations', 'presentation' ),
        'not_found' => _x( 'No presentations found', 'presentation' ),
        'not_found_in_trash' => _x( 'No presentations found in Trash', 'presentation' ),
        'parent_item_colon' => _x( 'Parent Prasentation:', 'presentation' ),
        'menu_name' => _x( 'Presentations', 'presentation' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Template Reveal.js Presentations',
        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes','wpcom-markdown'),
        'taxonomies' => array( 'category', 'post_tag', 'page-category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'presentation', $args );
}
add_action("admin_init", "admin_init");
 
function admin_init(){
  add_meta_box("presentation_details", "presentation Details", "presentation_details", "presentation", "normal", "high");
}
function presentation_details(){
	global $post;
	$custom =get_post_custom($post->ID);
	$reveal_theme = $custom["reveal_theme"][0];
	$reveal_pagedesc = $custom["reveal_pagedesc"][0];
	$plugin_status=$custom["plugin_status"][0];
	$status_update_date=$custom["status_update_date"][0];
	$effected_version=$custom["effected_version"][0];
	$plugin_description=$custom["plugin_description"][0];
	$access_level=$custom["access_level"][0];
?>
<table>
<th><td></td><td></td></th>
<tr>
	<td><label>Presentation theme</label></td>
    <td>
<select name="reveal_theme" type="text">
  <?php 
  $list=glob(dirname(__FILE__). '/reveal.js/css/theme/*.css');
foreach ($list as $file){
  print "\n<option value=". basename($file);
  if ( basename($file) == $reveal_theme )
  {
    print " selected='selected' ";
  }
  print ">" . basename($file) ."</option>";
}
  ?>
</select>
    </td>
</tr>
</table>
  <?php
}
add_action('save_post', 'save_details');
function save_details(){
  global $post;
  // select which theme to use for this presentation
  update_post_meta($post->ID, "reveal_theme", $_POST["reveal_theme"]);
  // if this option is selected then imort the powerpoint style css
  update_post_meta($post->ID, "reveal_powerpoint", $_POST["reveal_powerpoint"]);
  // add csutom css listed in this box in the post code.
  update_post_meta($post->ID, "reveal_custom_css", $_POST["reveal_custom_css"]);
  // select which all pluging needs to be enabled
  update_post_meta($post->ID, "reveal_plugin", $_POST["reveal_plugin"]);
  // custom header and footer for the prsentation
  // file upload and option to select file from media
  // suggest it will look bad if they select wrong dimensions
  update_post_meta($post->ID, "reveal_footer", $_POST["reveal_footer"]);
  update_post_meta($post->ID, "reveal_header", $_POST["reveal_header"]);
  // default reveal.js slide dimensions.
  update_post_meta($post->ID, "reveal_dimensions", $_POST["reveal_dimensions"]);
  // controls: true,
  //       slideNumber: true,
  //       progress: true,
  //       history: true,
  //       center: false,
          // help: true,


}
/*
Template allocation for various roles.
single page gives out details
disclosures page gives out tabular place
*/
function my_temp_inc($original_template)
{
	if (is_feed())
	{
		return $original_template;
	} 
  if (get_post_type( $post ) == "presentation" && !is_feed() && is_archive())
  {
    return plugin_dir_path( __FILE__ ) . 'presentations-template.php';
  }
  if (get_post_type( $post ) == "presentation" && !is_feed() && is_category() )
  {
    return plugin_dir_path( __FILE__ ) . 'presentations-template.php';
  }
  if (is_page('presentation') && !is_feed())
  {
    return plugin_dir_path( __FILE__ ) . '/presentations-template.php';
  }
  if (is_page('presentations') && !is_feed())
  {
    return plugin_dir_path( __FILE__ ) . '/presentations-template.php';
  }
  if (get_post_type( $post ) == "presentation" && !is_feed()){
		return plugin_dir_path( __FILE__ ) . 'single-presentation.php';
	}
	return $original_template;	
}
add_filter( 'template_include', 'my_temp_inc' );
/*
Add all types in feed
*/

//add_filter('request', 'smashing_custom_feed');
function smashing_custom_feed( $vars ) {
    if ( isset( $vars['feed'] ) ) {
        $vars['post_type'] = get_post_types();
    }
    return $vars;
}
/*
Add custom post type in categories and tags
*/
function add_custom_types_to_tax( $query ) {
	if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

	// Get all your post types
	$post_types = get_post_types();
	$query->set( 'post_type', $post_types );
	return $query;
	}
}
add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );

//Adding Default content for all presentations

add_filter( 'default_content', 'my_editor_content', 10, 2 );

function my_editor_content( $original_content,$post ) {
  if ( "presentation" == $post->post_type ) {
    $content="
# Title slide

---

# Next slide

--

# Vertical slide

--

# End slide

";
    return $content;
  }
  else
  {
    return $original_content;
  }
  
};
// This will disable richtext for presentation type
add_filter('user_can_richedit', 'disable_wysiwyg_for_CPT');
function disable_wysiwyg_for_CPT($default) {
  global $post;
  if ('presentation' == get_post_type($post))
    return false;
  return $default;
}
//Adding a search bar in main menu.
add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {
 
        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();
 
        $items .= '<li class="menu-item menu-item-search">' . $searchform . '</li>';
 
    return $items;
}
?>