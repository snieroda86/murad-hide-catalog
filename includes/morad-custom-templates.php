<?php

if(!class_exists('Morad_Custom_Templates')){
	class Morad_Custom_Templates{
		public function __construct(){
			add_filter( 'theme_page_templates', array( $this , 'add_page_template_to_dropdown') );
			add_filter( 'template_include', array( $this , 'change_page_template') , 99 );
		}

		/**
		* Add page templates.
		*
		* @param  array  $templates  The list of page templates
		*
		* @return array  $templates  The modified list of page templates
		*/
		function add_page_template_to_dropdown( $templates )
		{
		  $templates['pliki-do-pobrania-panel.php'] =  __( 'Pliki do pobrania', 'hide-calatogue' );
		  return $templates;
		}


		/**
		 * Change the page template to the selected template on the dropdown
		 * 
		 * @param $template
		 *
		 * @return mixed
		 */
		function change_page_template($template) 
		{
			
			global $post;
		    $custom_template_slug   = 'pliki-do-pobrania-panel.php';
		    $page_template_slug     = get_page_template_slug( $post->ID );

		    if( $page_template_slug == $custom_template_slug ){
		        $template = plugin_dir_path(__DIR__).'templates/'.$page_template_slug;
		    }

		    return $template;


		}

		
	}
}