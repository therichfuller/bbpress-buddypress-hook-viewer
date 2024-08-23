<?php

function etivite_hv_import_groups_loop_doactions( $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_do_action WHERE ". $table ."_version = %s  GROUP BY action_tag", $ver ) );
}
function etivite_hv_import_groups_loop_applyfilters( $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_apply_filters WHERE ". $table ."_version = %s  GROUP BY filter_tag", $ver ) );
}

function etivite_hv_import_groups_loop_doaction_by( $data, $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_do_action WHERE ". $table ."_version = %s AND action_tag = %s", $ver, $data ) );
}
function etivite_hv_import_groups_loop_addaction_by( $data, $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_add_action WHERE ". $table ."_version = %s AND action_tag = %s", $ver, $data ) );
}

function etivite_hv_import_groups_loop_applyfilter_by( $data, $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_apply_filters WHERE ". $table ."_version = %s AND filter_tag = %s", $ver, $data ) );
}
function etivite_hv_import_groups_loop_addfilter_by( $data, $table, $ver) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ". $wpdb->base_prefix ."hf_". $table ."_add_filter WHERE ". $table ."_version = %s AND filter_tag = %s", $ver, $data ) );
}

function etivite_hv_import_groups_loop_doaction_source( $id, $table ) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT s.*, f.file_path, f.component, f.core, f.plugin_path, f.svn_path FROM ". $wpdb->base_prefix ."hf_". $table ."_do_action_source_child s, ". $wpdb->base_prefix ."hf_". $table ."_files f  WHERE s.file_id = f.id AND s.do_action_id = %d", $id ) );
}
function etivite_hv_import_groups_loop_addaction_source( $id, $table ) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT s.*, f.file_path, f.component, f.core, f.plugin_path, f.svn_path FROM ". $wpdb->base_prefix ."hf_". $table ."_add_action_source_child s, ". $wpdb->base_prefix ."hf_". $table ."_files f  WHERE s.file_id = f.id AND s.add_action_id = %d", $id ) );
}

function etivite_hv_import_groups_loop_applyfilters_source( $id, $table ) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT s.*, f.file_path, f.component, f.core, f.plugin_path, f.svn_path FROM ". $wpdb->base_prefix ."hf_". $table ."_apply_filters_source_child s, ". $wpdb->base_prefix ."hf_". $table ."_files f  WHERE s.file_id = f.id AND s.apply_filters_id = %d", $id ) );
}
function etivite_hv_import_groups_loop_addfilter_source( $id, $table ) {
	global $wpdb, $bp;

	return $wpdb->get_results( $wpdb->prepare( "SELECT s.*, f.file_path, f.component, f.core, f.plugin_path, f.svn_path FROM ". $wpdb->base_prefix ."hf_". $table ."_add_filter_source_child s, ". $wpdb->base_prefix ."hf_". $table ."_files f  WHERE s.file_id = f.id AND s.add_filter_id = %d", $id ) );
}

function etivite_hv_import_groups_loop_function_source( $id, $table ) {
	global $wpdb, $bp;

	return $wpdb->get_row( $wpdb->prepare( "SELECT s.*, f.file_path, f.component, f.core, f.plugin_path, f.svn_path FROM ". $wpdb->base_prefix ."hf_. $table ._function_source s, ". $wpdb->base_prefix ."hf_. $table ._files f  WHERE s.file_id = f.id AND s.function = %s", $id ) );
}

function etivite_hv_import_groups_source_shortcodeit_raw( $source, $startline, $line ) {

	$source = implode ("", $source);
	
	$startline = $startline + 1;

	$source = '[php firstline="'. $startline .'" toolbar="false" highlight="'. $line .'"]' . $source . '[/php]';

	return $source;
}



function etivite_hv_import_posts_bp( $plugin = 'BuddyPress', $ver = HOOK_BP_VERSION, $table = 'bp') {
	global $wpdb;
	
	//loop over do_actions	
	$doactions = etivite_hv_import_groups_loop_doactions( $table, $ver );
	if ( $doactions ) {
		
		$addactionst = 0;
	
		$c1 = 0;
		$c1last = count($doactions) - 1;
		foreach( $doactions as $da ) {

			$hf_content = '';
			$component_a = array();
			$file_a = array();

			$hf_content .= '<ul id="do_action" class="item-list">';

			$doactionsi = etivite_hv_import_groups_loop_doaction_by( $da->action_tag, $table, $ver );
			foreach( $doactionsi as $do_action ) {
			
				$hf_content .= '<li class="item-list-item trigger"><div class="trigger-call"><pre>'. $do_action->action_raw_call .'</pre></div><div id="topic-tags">';
													
/*
				if ( $do_action->action_arg_num > 0 ) {
					$hf_content .= '<h3>Args ('. $do_action->action_arg_num .'):</h3><ul class="tags-list list:tag" id="tags-list">';
					$do_action->action_arg = maybe_unserialize( $do_action->action_arg );
					
					foreach( $do_action->action_arg as $arg ) {
						$hf_content .= '<li>'. $arg .'</li>';
					}
					$hf_content .= '</ul>';
				}
*/
				$hf_content .= '</div><div class="trigger-source"><h2>Source Reference:</h2><ul id="trigger-source-list-'. $do_action->id.'" class="item-list">';
	
				$hia = 0;
				foreach( etivite_hv_import_groups_loop_doaction_source( $do_action->id, $table ) as $do_action_src ) {
					$hf_content .= '<li id="trigger-source-'. $do_action_src->line_num .'"><div class="trigger-source-meta"><h5>Component: <span class="highlight">'. $do_action_src->core .'</span>';
															
					if ($do_action_src->component) { 
						$hf_content .= ':: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $do_action_src->component .'/">'. $do_action_src->component .'</a></span>';
						$component_a[] = $do_action_src->component;
					}
						
					$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $do_action_src->plugin_path ) .'/">'. $do_action_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $do_action_src->svn_path .'#L'. $do_action_src->line_num .'">Trac Source</a></span> Line: <span class="highlight">'. $do_action_src->line_num .'</span></h5></div>';
					$file_a[] = $do_action_src->plugin_path;
					$hf_content .= '<div class="trigger-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $do_action_src->source ), $do_action_src->start_line, $do_action_src->line_num ) .'</div></li>';
				}
				
				$hf_content .= '</ul></div></li>';
			
			}
			
			$hf_content .= '</ul>';
			$c1++;
			
			
			//loop over add_action	
			$addactionsi = etivite_hv_import_groups_loop_addaction_by( $da->action_tag, $table, $ver ); 
			$count_aa = count( $addactionsi );
			if ($addactionsi) {
	
				$c2 = 0;
				$c2last = count($addactionsi) - 1;
				foreach( $addactionsi as $add_action ) {
	
					$hf_content .= '<div id="add_action" class="item-header"><h2 id="hook" >Hook: add_action: <span class="hook-highlight">'. $do_action->action_tag .'</span> instances ('. count($addactionsi) .')</h2></div><ul id="add-action-list" class="item-list">';
	
					$hf_content .= '<li class="item-list-item trigger"><div class="hook-call"><pre>'. $add_action->action_raw_call .'</pre></div><div class="hook-meta"><h5>$function_to_add: '. $add_action->function_call .'</h5><h5>$priority: <span class="highlight">'. $add_action->priority .'</span></h5><h5>$accepted_args: <span class="highlight">'. $add_action->action_arg_num .'</span></h5></div><div class="hook-source"><h2>Source Reference:</h2><ul id="hook-source-list-'. $add_action->id .'">';
	
					$hia = 0;
					foreach( etivite_hv_import_groups_loop_addaction_source( $add_action->id, $table ) as $add_action_src ) {
													
						$hf_content .= '<li id="hook-source-'. $add_action_src->line_num.'"><div class="hook-source-meta"><h5>Component: <span class="highlight">'. $add_action_src->core .'</span>';
						if ($add_action_src->component) {
							$hf_content .= ' :: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $add_action_src->component .'/">'. $add_action_src->component .'</a></span>';
						}
							
						$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $add_action_src->plugin_path ) .'/">'. $add_action_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $add_action_src->svn_path .'#L'. $add_action_src->line_num .'">Trac Source</a></span> Line: <span class="highlight">'. $add_action_src->line_num .'</span></h5></div>';
						$hf_content .= '<div class="hook-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $add_action_src->source ), $add_action_src->start_line, $add_action_src->line_num ) .'</div></li>';
					}
					
					$hf_content .= '</ul></div>';
										
					$func_src = etivite_hv_import_groups_loop_function_source( $add_action->function_call, $table );
					if( $func_src ) {
										
						$hf_content .= '<div class="function-source"><h2>Source Reference ( $function_to_add: '. $add_action->function_call .' ):</h2>';
						$hf_content .= '<ul id="function-source-list-'. $add_action->id .'"><li id="function-source-'. $func_src->start_line .'"><div class="function-source-meta"><h5>Component: <span class="highlight">'. $func_src->core .'</span>';
	
						if ($func_src->component) { 
							$hf_content .= ' :: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $func_src->component .'/">'. $func_src->component .'</a></span>';
						}
	
						$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $func_src->plugin_path ) .'"/>'. $func_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $func_src->svn_path .'#L'. $func_src->start_line .'">Trac Source</a></span> Line: <span class="highlight">'. $func_src->start_line .'</span></h5></div>';
	
						if ( $func_src->doc ) {
							$hf_content .= '<div class="function-doc"><h4>Function Docs</h4><pre>'. $func_src->doc .'</pre></div>';
						}
	
						$hf_content .= '<div class="function-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $func_src->source ), $func_src->start_line, $func_src->line_num ) .'</div>';
						
						$hf_content .= '</li></ul></div>';
										
					}
									
					$hf_content .= '</li></ul>';
					
					$addactionst =+ count($addactionsi);
				}
							
			}		
			
			
			//add do_action cpt
			$inserted_trigger   =  wp_insert_post( array(
				//'ID' => !empty( $da->wp_post_id ) ? $da->wp_post_id : '',
				'post_author'    => get_current_user_id(),
				'post_content'   => $hf_content,
				'post_title'     => $da->action_tag,
				'post_excerpt'   => '<pre>'. $do_action->action_raw_call .'</pre>',
				'post_status'    => 'publish',
				//'comment_status' => 'open',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_type'      => 'hf_trigger',
				'post_name'      => sanitize_title( $da->action_tag ),
				'tax_input'      => array( 'hf_type_tag' => 'do_action', 'hf_component_tag' =>  $component_a, 'hf_file_tag' => $file_a, 'hf_software_tag' => $plugin, 'hf_software_version_tag' => $ver )

			) );
			//add buddypress version
			update_post_meta( $inserted_trigger, '_hf_api_type', 'do_action' );
			update_post_meta( $inserted_trigger, '_hf_api_core', $plugin );
			update_post_meta( $inserted_trigger, '_hf_addaction_count', $count_aa );
			update_post_meta( $inserted_trigger, '_hf_software_version', $ver );
			update_post_meta( $inserted_trigger, '_hf_software', $plugin );
			
			$q = $wpdb->prepare( "UPDATE ". $wpdb->base_prefix ."hf_". $table ."_do_action SET wp_post_id = %d WHERE action_tag = %s", $inserted_trigger, $da->id );
			$wpdb->query($q);
			
		}
		
	}
	
	
	//loop over apply_filters
	$applyfilters = etivite_hv_import_groups_loop_applyfilters( $table, $ver );
	if (applyfilters) {
		$addfiltert = 0;
	
		$c1 = 0;
		$c1last = count($applyfilters) - 1;
		foreach( $applyfilters as $af ) {

			$hf_content = '';
			$component_a = array();
			$file_a = array();

			$hf_content .= '<ul id="apply_filters" class="item-list">';
			
			$applyfiltersi = etivite_hv_import_groups_loop_applyfilter_by( $af->filter_tag, $table, $ver );
			foreach( $applyfiltersi as $apply_filter ) {
				
				$hf_content .= '<li class="item-list-item trigger"><div class="trigger-call"><pre>'. $apply_filter->filter_raw_call .'</pre></div><div id="topic-tags">';
				
/*
				if ( $apply_filter->filter_arg_num > 0 ) {
					$hf_content .= '<h3>Args ('. $apply_filter->filter_arg_num .'):</h3><ul class="tags-list list:tag" id="tags-list">';
					
					$apply_filter->filter_arg = maybe_unserialize( $apply_filter->filter_arg );							
					foreach( $apply_filter->filter_arg as $arg ) {
						$hf_content .= '<li>'. $arg .'</li>';
					}
					
					$hf_content .= '</ul>';
				}
*/
				
				$hf_content .= '</div><div class="trigger-source"><h2>Source Reference:</h2><ul id="apply-source-list-'. $apply_filter->id .'" class="item-list">';

				$hia = 0;
				foreach( etivite_hv_import_groups_loop_applyfilters_source( $apply_filter->id, $table ) as $apply_filter_src ) {
					
					$hf_content .= '<li id="trigger-source-'. $apply_filter_src->line_num .'" class="item-list-item"><div class="trigger-source-meta"><h5>Component: <span class="highlight">'. $apply_filter_src->core .'</span>';
					
					if ($apply_filter_src->component) { 
						$hf_content .= ' :: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $apply_filter_src->component .'/">'. $apply_filter_src->component .'</a></span>';
						$component_a[] = $apply_filter_src->component;
					}
					$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $apply_filter_src->plugin_path ) .'">'. $apply_filter_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $apply_filter_src->svn_path .'#L'. $apply_filter_src->line_num .'">Trac Source</a></span> Line: <span class="highlight">'. $apply_filter_src->line_num .'</span></h5></div>';
					$file_a[] = $apply_filter_src->plugin_path;
					
					$hf_content .= '<div class="trigger-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $apply_filter_src->source ), $apply_filter_src->start_line, $apply_filter_src->line_num ) .'</div></li>';
				}
				
				$hf_content .= '</ul></div></li>';
			}
			
			$hf_content .= '</ul>';
			$c1++;
			
			
			$addfiltersi = etivite_hv_import_groups_loop_addfilter_by( $af->filter_tag, $table, $ver ); 
			$count_af = count( $addfiltersi );
			if ($addfiltersi) {

				$hf_content .= '<div id="add_filter" class="item-header"><h2 id="hook">Hook: add_filter: <span class="hook-highlight">'. $af->filter_tag .'</span> instances ('. count($addfiltersi) .')</h2></div><ul id="add-action-list" class="item-list">';		

				$c2 = 0;
				$c2last = count($addfiltersi) - 1;
				foreach( $addfiltersi as $add_filter ) {
					$hf_content .= '<li class="item-list-item trigger"><div class="hook-call"><pre>'. $add_filter->filter_raw_call .'</pre></div>';
					$hf_content .= '<div class="hook-meta"><h5>$tag: <span class="highlight">'. $add_filter->filter_tag .'</span></h5><h5>$function_to_add: '. $add_filter->function_call .'</h5><h5>$priority: <span class="highlight">'. $add_filter->priority .'</span></h5><h5>$accepted_args: <span class="highlight">'. $add_filter->filter_arg_num.'</span></h5></div><div class="hook-source"><h2>Source Reference:</h2><ul id="hook-source-list-'. $add_filter->id .'">';

					$hia = 0;
					foreach( etivite_hv_import_groups_loop_addfilter_source( $add_filter->id, $table ) as $add_filter_src ) {
						
						$hf_content .= '<li id="hook-source-'. $add_filter_src->line_num .'"><div class="hook-source-meta"><h5>Component: <span class="highlight">'. $add_filter_src->core.'</span>';
						
						if ($add_filter_src->component) { 
							$hf_content .= ' :: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $add_filter_src->component .'/">'. $add_filter_src->component .'</a></span>';
						}
						$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $add_filter_src->plugin_path ) .'">'. $add_filter_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $add_filter_src->svn_path .'#L'. $add_filter_src->line_num .'">Trac Source</a></span> Line: <span class="highlight">'. $add_filter_src->line_num .'</span></h5></div>';

						$hf_content .= '<div class="hook-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $add_filter_src->source ), $add_filter_src->start_line, $add_filter_src->line_num ) .'</div></li>';
					}
											
					$hf_content .= '</ul></div>';
									
					$func_src = etivite_hv_import_groups_loop_function_source( $add_filter->function_call, $table );
					if( $func_src ) {
									
						$hf_content .= '<div class="function-source"><h2>Source Reference ( $function_to_add: '. $add_filter->function_call .' ):</h2>';

						$hf_content .= '<ul id="function-source-list-'. $add_filter->id .'"><li id="function-source-'. $func_src->start_line .'"><div class="function-source-meta"><h5>Component: <span class="highlight">'. $func_src->core .'</span>';
							
						if ($func_src->component) { 
							$hf_content .= ' :: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/component/'. $func_src->component .'">'. $func_src->component .'</a></span>';
						}
						$hf_content .= '</h5><h5>File: <span class="highlight"><a href="'. get_option('siteurl') .'/api-hooks/'. strtolower ( $plugin ) .'/path/'. sanitize_title( $func_src->plugin_path ) .'/">'. $func_src->plugin_path .'</a></span> :: <span class="highlight"><a href="'. $func_src->svn_path .'#L'. $func_src->start_line .'">Trac Source</a></span> Line: <span class="highlight">'. $func_src->start_line .'</span></h5></div>';

						if ( $func_src->doc ) {
							$hf_content .= '<div class="function-doc"><h4>Function Docs</h4><pre>'. $func_src->doc .'</pre></div>';
						}

						$hf_content .= '<div class="function-source">'. etivite_hv_import_groups_source_shortcodeit_raw( maybe_unserialize( $func_src->source ), $func_src->start_line, $func_src->line_num ) .'</div></li></ul></div>';
									
					}
									
					$hf_content .= '</li>';
					$addfiltert =+ count($addfiltersi);
				}
				
				$hf_content .= '</ul>';
						
			}
			
			
			
			//add apply_filters cpt
			$inserted_trigger   =  wp_insert_post( array(
				//'ID' => !empty( $af->wp_post_id ) ? $af->wp_post_id : '',
				'post_author'    => get_current_user_id(),
				'post_content'   => $hf_content,
				'post_title'     => $af->filter_tag,
				'post_excerpt'   => '<pre>'. $af->filter_raw_call .'</pre>',
				'post_status'    => 'publish',
				//'comment_status' => 'open',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_type'      => 'hf_trigger',
				'post_name'      => sanitize_title( $af->filter_tag ),
				'tax_input'      => array( 'hf_type_tag' => 'apply_filters', 'hf_component_tag' => $component_a, 'hf_file_tag' => $file_a, 'hf_software_tag' => $plugin, 'hf_software_version_tag' => $ver )

			) );
			//add buddypress version
			update_post_meta( $inserted_trigger, '_hf_api_type', 'apply_filters' );
			update_post_meta( $inserted_trigger, '_hf_api_core', $plugin );
			update_post_meta( $inserted_trigger, '_hf_addfilter_count', $count_af );
			update_post_meta( $inserted_trigger, '_hf_software_version', $ver );
			update_post_meta( $inserted_trigger, '_hf_software', $plugin );
			
			$q = $wpdb->prepare( "UPDATE ". $wpdb->base_prefix ."hf_". $table ."_apply_filters SET wp_post_id = %d WHERE filter_tag = %s", $inserted_trigger, $af->filter_tag );
			$wpdb->query($q);
				
		}

	}
	
	
}

function etivite_hv_load() {
	global $wpdb;
	
	set_time_limit(0);
	ignore_user_abort(true); 
	ini_set('memory_limit','64M');
	
	//clear the table posts
	//$wpdb->query("DELETE p, pm FROM ". $wpdb->base_prefix ."posts p, ". $wpdb->base_prefix ."postmeta pm where p.post_type = 'hf_trigger' AND p.ID = pm.post_id");
	//$wpdb->query("DELETE p, pm FROM ". $wpdb->base_prefix ."posts p, ". $wpdb->base_prefix ."postmeta pm where p.post_type = 'hf_software' AND p.ID = pm.post_id");

	
	//etivite_hv_import_posts_bp( 'BuddyPress', HOOK_BP_VERSION, 'bp');
	//etivite_hv_import_posts_bp( 'bbPress', HOOK_BBP_VERSION, 'bbp');
}

//add_action( 'admin_footer', 'etivite_hv_load', 9000 );
?>
