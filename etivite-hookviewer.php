<?php

//TODO - fix some issues with tax urls not registering... ie, comes up with %tag% in urls which are internally linked

function etivite_hv_register_post_types() {

	/** Software ***********************************************************/

	$software['labels'] = array(
		'name'               => 'Software',
		'menu_name'          => 'Software',
		'singular_name'      => 'Software',
		'all_items'          => 'All Software',
		'add_new'            => 'New Software',
		'add_new_item'       => 'Create New Software',
		'edit'               => 'Edit',
		'edit_item'          => 'Edit Software',
		'new_item'           => 'New Software',
		'view'               => 'View Software',
		'view_item'          => 'View Software',
		'search_items'       => 'Search Software',
		'not_found'          => 'No software found',
		'not_found_in_trash' => 'No software found in Trash',
		'parent_item_colon'  => 'Parent Software:'
	);
	$software['rewrite'] = array(
		'slug'       => 'api-hooks',
		'with_front' => false
	);
	$software['supports'] = array(
		'title',
		'thumbnail',
		'editor'
	);
	$software_cpt['forum'] = array(
		'labels'              => $software['labels'],
		'rewrite'             => $software['rewrite'],
		'supports'            => $software['supports'],
		'description'         => 'API Hooks and Filters',
		'capability_type'     => 'post',
		'menu_position'       => 100,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'show_in_nav_menus'   => true,
		'public'              => true,
		'show_ui'             => true,
		'hierarchical'        => false,
		'query_var'           => true,
		'menu_icon'           => ''
	);
	//register_post_type( 'hf_software', $software_cpt['forum'] );


	/** Triggers (apply_filters, do_action) ***********************************************************/

	$triggers['labels'] = array(
		'name'               => 'Trigger',
		'menu_name'          => 'Triggers',
		'singular_name'      => 'Trigger',
		'all_items'          => 'All Triggers',
		'add_new'            => 'New Trigger',
		'add_new_item'       => 'Create New Trigger',
		'edit'               => 'Edit',
		'edit_item'          => 'Edit Trigger',
		'new_item'           => 'New Trigger',
		'view'               => 'View Triggers',
		'view_item'          => 'View Trigger',
		'search_items'       => 'Search Triggers',
		'not_found'          => 'No Triggers found',
		'not_found_in_trash' => 'No Triggers found in Trash',
		'parent_item_colon'  => 'Trigger: ',
	);
	$triggers['rewrite'] = array(
		'slug'       => 'api-hooks/%hf_software_tag%/trigger/%hf_type_tag%',
		'with_front' => false
	);
	$triggers['supports'] = array(
		'title',
		'editor',
		'comments'
	);
	$triggers_cpt['topic'] = array(
		'labels'              => $triggers['labels'],
		'rewrite'             => $triggers['rewrite'],
		'supports'            => $triggers['supports'],
		'description'         => 'API Triggers',
		//'has_archive'         => true,
		'has_archive'         => 'api-hooks/%hf_software_tag%/trigger/%hf_type_tag%',
		'capability_type'     => 'post',
		'menu_position'       => 101,
		'show_in_nav_menus'   => false,
		'public'              => true,
		'show_ui'             => true,
		'hierarchical'        => false,
		'query_var'           => true,
		'menu_icon'           => ''
	);
	register_post_type( 'hf_trigger', $triggers_cpt['topic'] );
		
		
/*
api-hooks/%hf_software%/trigger/%hf_type_tag%/?$ 	


add_rewrite_rule( '^nutrition/([^/]*)/([^/]*)/?','index.php?p=12&food=$matches[1]&variety=$matches[2]','top' );

api-hooks/%hf_software%/trigger/%hf_type_tag%/feed/(feed|rdf|rss|rss2|atom)/?$ 	

      post_type: hf_trigger
           feed: (feed|rdf|rss|rss2|atom)

api-hooks/%hf_software%/trigger/%hf_type_tag%/(feed|rdf|rss|rss2|atom)/?$ 	

      post_type: hf_trigger
           feed: (feed|rdf|rss|rss2|atom)

api-hooks/%hf_software%/trigger/%hf_type_tag%/page/([0-9]{1,})/?$
*/
		

	//force rewrite to taxonomy-<taxname>.php template
	add_rewrite_rule( 'api-hooks/([^/]*)/component/([^/]*)/?', 'index.php?hf_var_software=$matches[1]&hf_component_tag=$matches[2]', 'top' );
	add_rewrite_rule( 'api-hooks/([^/]*)/path/([^/]*)/?', 'index.php?hf_var_software=$matches[1]&hf_file_tag=$matches[2]', 'top' );
	add_rewrite_rule( 'api-hooks/([^/]*)/triggers/([^/]*)/?', 'index.php?hf_var_software=$matches[1]&hf_type_tag=$matches[2]&_type_tag=$matches[2]', 'top' );
	//add_rewrite_rule( 'api-hooks/([^/]*)/triggers/?', 'index.php?hf_software_tag=$matches[1]', 'top' );
		
	flush_rewrite_rules( false );

}
	
function etivite_hv_register_taxonomies() {


	/** Software ***********************************************************/

	$software_tag['labels'] = array(
		'name'          => 'Software',
		'singular_name' => 'Software',
		'search_items'  => 'Search Software',
		'popular_items' => 'Popular Software',
		'all_items'     => 'All Software',
		'edit_item'     => 'Edit Software',
		'update_item'   => 'Update Software',
		'add_new_item'  => 'Add New Software',
		'new_item_name' => 'New Software Name',
		'view_item'     => 'View Software'
	);
	$software_tag['rewrite'] = array(
		'slug'       => 'api-hooks',
		'with_front' => false
	);
	$software_tag_tt = array(
		'labels'                => $software_tag['labels'],
		'rewrite'               => $software_tag['rewrite'],
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true
	);
	register_taxonomy(
		'hf_software_tag', 
		'hf_trigger',  // The topic post type
		$software_tag_tt
	);


	/** Trigger Hooks - Types (do_action, add_action, apply_filters, add_filter) ***********************************************************/
	
	$trigger_type_tag['labels'] = array(
		'name'          => 'Trigger Type',
		'singular_name' => 'Type',
		'search_items'  => 'Search Trigger Type',
		'popular_items' => 'Popular Trigger Types',
		'all_items'     => 'All Trigger Types',
		'edit_item'     => 'Edit Trigger Type',
		'update_item'   => 'Update Trigger Type',
		'add_new_item'  => 'Add New Trigger Type',
		'new_item_name' => 'New Trigger Type Name',
		'view_item'     => 'View Trigger Type'
	);
	$trigger_type_tag['rewrite'] = array(
		'slug'       => 'api-hooks/%hf_software_tag%/triggers',
		'with_front' => false
	);
	$trigger_type_tag_tt = array(
		'labels'                => $trigger_type_tag['labels'],
		'rewrite'               => $trigger_type_tag['rewrite'],
		'update_count_callback' => '_update_post_term_count',
		'has_archive' => 'api-hooks/%hf_software_tag%/triggers',
		'query_var'             => true,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true
	);
	register_taxonomy(
		'hf_type_tag',
		'hf_trigger',  // The topic post type
		$trigger_type_tag_tt
	);
	
	
	/** Trigger Hooks - Components (bp-activity, bp-core, etc) ***********************************************************/
	
	$component_tag['labels'] = array(
		'name'          => 'Component',
		'singular_name' => 'Component',
		'search_items'  => 'Search Components',
		'popular_items' => 'Popular Components',
		'all_items'     => 'All Components',
		'edit_item'     => 'Edit Component',
		'update_item'   => 'Update Component',
		'add_new_item'  => 'Add New Component',
		'new_item_name' => 'New Component Name',
		'view_item'     => 'View Component'
	);
	$component_tag['rewrite'] = array(
		'slug'       => 'api-hooks/%hf_software_tag%/component',
		'with_front' => false
	);
	$component_tag_tt = array(
		'labels'                => $component_tag['labels'],
		'rewrite'               => $component_tag['rewrite'],
		'update_count_callback' => '_update_post_term_count',
		'has_archive' => 'api-hooks/%hf_software_tag%/component',
		'query_var'             => true,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true
	);
	register_taxonomy(
		'hf_component_tag',
		'hf_trigger',  // The topic post type
		$component_tag_tt
	);
	
	
	/** Trigger Hooks - File ***********************************************************/

	$file_tag['labels'] = array(
		'name'          => 'File',
		'singular_name' => 'File',
		'search_items'  => 'Search Files',
		'popular_items' => 'Popular Files',
		'all_items'     => 'All Files',
		'edit_item'     => 'Edit File',
		'update_item'   => 'Update File',
		'add_new_item'  => 'Add New File',
		'new_item_name' => 'New File Name',
		'view_item'     => 'View File'
	);
	$file_tag['rewrite'] = array(
		'slug'       => 'api-hooks/%hf_software_tag%/path',
		'with_front' => false
	);
	$file_tag_tt = array(
		'labels'                => $file_tag['labels'],
		'rewrite'               => $file_tag['rewrite'],
		'update_count_callback' => '_update_post_term_count',
		'has_archive' => 'api-hooks/%hf_software_tag%/path',
		'query_var'             => true,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true
	);
	register_taxonomy(
		'hf_file_tag',
		'hf_trigger', // The topic post type
		$file_tag_tt
	);
	
	
	/** Software Version ***********************************************************/

	$softwarev_tag['labels'] = array(
		'name'          => 'Software Version',
		'singular_name' => 'Software Version',
		'search_items'  => 'Search Software Versions',
		'popular_items' => 'Popular Software Versions',
		'all_items'     => 'All Software Versions',
		'edit_item'     => 'Edit Software Version',
		'update_item'   => 'Update Software Version',
		'add_new_item'  => 'Add New Software Version',
		'new_item_name' => 'New Software Version Name',
		'view_item'     => 'View Software Version'
	);
	$softwarev_tag['rewrite'] = array(
		'slug'       => 'api-hooks/%hf_software_tag%',
		'with_front' => false
	);
	$softwarev_tag_tt = array(
		'labels'                => $softwarev_tag['labels'],
		//'rewrite'               => $softwarev_tag['rewrite'],
		'update_count_callback' => '_update_post_term_count',
		'rewrite'               => false,
		'query_var'             => true,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true
	);
	register_taxonomy(
		'hf_software_version_tag', 
		'hf_trigger',  // The topic post type
		$softwarev_tag_tt
	);
		
		
}
add_action('init', 'etivite_hv_register_taxonomies');
add_action('init', 'etivite_hv_register_post_types');
	
	
function etivite_hv_filter_post_type_link($link, $post) {

	if ( false !== strpos( $link, '%hf_software_tag%' ) ) {
        if ($cats = get_the_terms($post->ID, 'hf_software_tag')) $link = str_replace('%hf_software_tag%', array_pop($cats)->slug, $link);
    }

	if ( false !== strpos( $link, '%hf_type_tag%' ) ) {
        if ($cats = get_the_terms($post->ID, 'hf_type_tag')) $link = str_replace('%hf_type_tag%', array_pop($cats)->slug, $link);
    }
    
    return $link;
    
}
add_filter('post_type_link', 'etivite_hv_filter_post_type_link', 10, 2);

function etivite_hv_filter_terms_link( $term_links ) {

	$hf_software = get_query_var('hf_software_tag');
	if ( !$hf_software ) $hf_software = get_query_var('hf_var_software');

	foreach ( $term_links as $key => $value ) {
		if ( false !== strpos( $value, '%hf_software_tag%' ) ) {
			$term_links[ $key ] = str_replace('%hf_software_tag%', $hf_software, $value);
		}
	}
    
    return $term_links;
    
}
add_filter( 'term_links-hf_component_tag', 'etivite_hv_filter_terms_link' );
add_filter( 'term_links-hf_file_tag', 'etivite_hv_filter_terms_link' );
add_filter( 'term_links-hf_type_tag', 'etivite_hv_filter_terms_link' );

function etivite_add_query_vars( $aVars ) {
    $aVars[] = "hf_var_software";
    return $aVars;
}
 
// hook add_query_vars function into query_vars
add_filter('query_vars', 'etivite_add_query_vars');


function get_terms_by_post_type_link( $taxonomies, $post_types, $path, $tax_slug ) {
	
	$links = get_terms_by_post_type( $taxonomies, $post_types, $tax_slug );
	
	if ($links) {
		foreach ( $links as $link )
			$thelinks[] = "<li><a href='" . home_url( $path . user_trailingslashit( esc_attr( $link->slug ) ) ) . "'>$link->name</a> ($link->count)</li>";
		
		return '<ul id="hf-list-'. $taxonomies .'-'. $tax_slug .'" class="hf-item-list">'. join( '', $thelinks) .'</ul>';
	}
	
}

function get_terms_by_post_type( $taxonomies, $post_types, $tax_slug ) {
	global $wpdb;

	$query = $wpdb->prepare( "SELECT t.*, COUNT(*) as count from $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id INNER JOIN $wpdb->postmeta AS pm ON pm.post_id = p.ID WHERE p.post_type IN('". $post_types ."') AND tt.taxonomy IN('". $taxonomies ."') AND pm.meta_key = '_hf_software' AND pm.meta_value = '". $tax_slug ."' GROUP BY t.term_id ORDER BY t.name");
		
	$results = $wpdb->get_results( $query );
	
	return $results;

}

// [bartag foo="foo-value"]
function gettermsbypttax_func( $atts ) {
	extract( shortcode_atts( array(
		'taxonomies' => 'hf_component_tag',
		'post_types' => 'hf_trigger',
		'path' => 'api-hooks/',
		'tax_slug' => 'buddypress'
	), $atts ) );

	return get_terms_by_post_type_link( $taxonomies, $post_types, $path, $tax_slug );
}
add_shortcode( 'gettermsbypttax', 'gettermsbypttax_func' );

// [bartag foo="foo-value"]
function getsearchforhftrigger_func( $atts ) {
	return '<form method="get" id="searchform" action="'. esc_url( home_url( '/' ) ) .'"><label for="s" class="assistive-text">Search</label><input type="text" class="field" name="s" id="s" placeholder="Search Actions and Filters" /><input type="submit" class="submit" name="submit" id="searchsubmit" value="Search" /><input type="hidden" name="post_type" id="post_type" value="hf_trigger" /></form>';
}
add_shortcode( 'getsearchforhftrigger', 'getsearchforhftrigger_func' );

function etivite_hv_wp_head_scripts() {

	if ( 'page' == get_post_type() && is_page( 'api-hooks' )) {

		wp_enqueue_script( "etivite_hv_wp_head", plugins_url( '/_inc/etivite.js', __FILE__ ), array( 'jquery' ) );
		
	}

}
add_action('wp_print_scripts', 'etivite_hv_wp_head_scripts');


function etivite_hv_wp_head_css() {

	if ( 'hf_trigger' == get_post_type() || 'page' == get_post_type() || is_page( 'api-hooks' ) || get_query_var('hf_var_software') == 'buddypress' ) {
		echo '<link rel="stylesheet" type="text/css" media="all" href="'. plugins_url( '/_inc/etivite.css', __FILE__ ) .'" />';
	}

}
add_action('wp_head', 'etivite_hv_wp_head_css');

function etivite_hv_wp_footer() {

	if ( 'hf_trigger' == get_post_type() || 'page' == get_post_type() || is_page( 'api-hooks' ) || get_query_var('hf_var_software') == 'buddypress' ) {
		echo '<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>';
	}

}
add_action('wp_footer', 'etivite_hv_wp_footer', 9999 );

function etivite_hv_wp_the_title( $title ) {

	if ( 'page' == get_post_type() ) {
		if ( is_page( 'api-hooks' ) ) {
			return $title .' <g:plusone size="small" href="http://etivite.com/api-hooks/"></g:plusone>';
		} elseif ( is_page( 'wordpress-plugins' ) ) {
			return $title .' <g:plusone size="small" href="http://etivite.com/wordpress-plugins/"></g:plusone>';
		}
	}
	return $title;

}
//add_filter('the_title', 'etivite_hv_wp_the_title');

/**
 * Display navigation to next/previous pages when applicable
 */
function etivite_hv_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'API Hooks navigation', 'twentyeleven' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> more hooks', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'previous hooks <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

?>
