<?php

if(!class_exists('Morad_Catalogue_Button_Shortcode')){
	class Morad_Catalogue_Button_Shortcode{
		public function __construct(){
			add_shortcode( 'murad_catalogue', array($this , 'custom_catalogue_shortcode') );
		}

		public function custom_catalogue_shortcode( $atts ) {
		    
		    $atts = shortcode_atts( array(
		        'post_id'       => get_the_ID(), 
		        'button_label'   => 'Pobierz katalog',
		    ), $atts );

		    $post_url = get_permalink( $atts['post_id'] );
		    $is_logged_in = is_user_logged_in();
		    ob_start();

		    ?>

		    <?php if ( $is_logged_in ) : ?>
           		<a class="elementor-button elementor-button-link elementor-size-sm elementor-animation-shrink" href="<?php echo esc_url( $post_url ); ?>" target="_blank">
					<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon elementor-align-icon-left">
								<i aria-hidden="true" class="fas fa-file-download"></i>			
						</span>
						<span class="elementor-button-text"><?php echo esc_html( $atts['button_label'] ); ?> </span>
					</span>
				</a>
	        <?php else : ?>
	            <a class="elementor-button elementor-button-link elementor-size-sm elementor-animation-shrink" href="#">
					<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon elementor-align-icon-left">
								<i aria-hidden="true" class="fas fa-file-download"></i>			
						</span>
						<span class="elementor-button-text"><?php echo esc_html('Zaloguj się aby pobrać katalog' , 'hide-calatogue' ); ?> </span>
					</span>
				</a>
	        <?php endif; ?>
		    

		    <?php

		    // Zakończenie buforowania i zwrócenie zawartości
		    return ob_get_clean();
		}
	}
}