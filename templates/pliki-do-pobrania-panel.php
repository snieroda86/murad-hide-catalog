<?php
/**
 * The template for displaying files to download page
 * @package WordPress
 * 
 */

get_header(); ?> 

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" style="padding:15px;" role="main">
			<?php if( !is_user_logged_in()): ?>
				<div class="elementor-container" style="max-width: 1200px;margin:auto;padding-top:60px;padding-bottom:120px;">
					<h4 style="text-transform: none;text-align: center;">Dostęp do treści na tej stronie mają tylko zalogowani użytkownicy</h4>
					<div style="padding-top: 40px; text-align: center;">
						<?php $registration_form_url = get_permalink(10471); ?>
						<a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo esc_url($registration_form_url); ?>" >
							<span class="elementor-button-content-wrapper">
								<span class="elementor-button-icon elementor-align-icon-left">
									<i aria-hidden="true" class="fas fa-shopping-cart"></i>			
								</span>
								<span class="elementor-button-text">Załóż konto i uzyskaj dostęp</span>
				            </span>
						</a>
					</div>	
				</div>
				
			<?php elseif( is_user_logged_in() ):

				$user_id = get_current_user_id();
				$client_priviliges = get_user_meta($user_id, 'client_priviliges', true);

				if ($client_priviliges && strpos($client_priviliges, 'panel_allow_access') !== false  ||  current_user_can('administrator')) {
			        // Start the Loop.
					while ( have_posts() ) :
						the_post();

						the_content();

					endwhile;
			    }

					
			endif;  ?>
			
			

		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php

get_footer();