<?php 
if(!class_exists('SystemyAluminiowe_CPT')){
	class SystemyAluminiowe_CPT{
		public function __construct(){
			add_action('init' , array($this , 'create_cpt'));
			add_filter( 'the_content', array($this , 'restrict_visibility') );

		}

		public function create_cpt(){
			    $labels = array(
			        'name'               => 'Systemy Aluminiowe',
			        'singular_name'      => 'System Aluminiowy',
			        'menu_name'          => 'Systemy Aluminiowe',
			        'parent_item_colon'  => 'Nadrzędny System Aluminiowy',
			        'all_items'          => 'Wszystkie Systemy Aluminiowe',
			        'view_item'          => 'Zobacz System Aluminiowy',
			        'add_new_item'       => 'Dodaj nowy System Aluminiowy',
			        'add_new'            => 'Dodaj nowy',
			        'edit_item'          => 'Edytuj System Aluminiowy',
			        'update_item'        => 'Aktualizuj System Aluminiowy',
			        'search_items'       => 'Szukaj Systemu Aluminiowego',
			        'not_found'          => 'Nie znaleziono',
			        'not_found_in_trash' => 'Nie znaleziono w koszu'
			    );

			    $args = array(
			        'label'               => 'systemy-aluminiowe',
			        'description'         => 'Custom Post Type dla Systemów Aluminiowych',
			        'labels'              => $labels,
			        'supports'            => array( 'title', 'editor' ),
			        'hierarchical'        => false,
			        'public'              => true,
			        'show_ui'             => true,
			        'show_in_menu'        => true,
			        'show_in_nav_menus'   => false,
			        'show_in_admin_bar'   => true,
			        'menu_position'       => 5,
			        'can_export'          => false,
			        'has_archive'         => false,
			        'rewrite'             => array( 'slug' => 'systemy-aluminiowe' ),
			        'capability_type'     => 'post',
			        'menu_icon'           => 'dashicons-list-view'
			        // 'taxonomies'          => array( 'category', 'post_tag' ),
			    );

			    register_post_type( 'systemy-aluminiowe', $args );
		}

		/*
		** Restricting visibility of custom post type to authenticated users
		*/

		public function restrict_visibility( $content ){
			global $post;
		    if ( $post->post_type == 'systemy-aluminiowe' ) {
		        if ( !is_user_logged_in() ) {
		            $content = 'Please login to view this post';
		        }
		    }
		    return $content;
		}

		


	}
}


 ?>