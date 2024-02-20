<?php 

if(!class_exists('Morad_Registration_Form_Shortcode')){
	class Morad_Registration_Form_Shortcode{
		public function __construct(){


			add_shortcode( 'murad_register_form', array($this , 'custom_register_form_shortcode') );

			add_action('template_redirect' , array( $this , 'save_new_user') );

			add_action('show_user_profile', array($this , 'custom_user_profile_fields' ) );
			add_action('edit_user_profile', array( $this , 'custom_user_profile_fields'));

			add_action('personal_options_update', array( $this , 'save_custom_user_profile_fields' ) );
			add_action('edit_user_profile_update',  array( $this , 'save_custom_user_profile_fields' ) );
		}

		public function custom_register_form_shortcode(){

			// only show the registration form to non-logged-in members
			if(!is_user_logged_in()) {
				
				// check to make sure user registration is enabled
				$registration_enabled = get_option('users_can_register');
				$output = '';
				// only show the registration form if allowed
				if($registration_enabled) {
					return $this->morad_registration_form_fields();
				} else {
					$output = __('Rejestracja nie jest możliwa' , 'hide-calatogue');
				}
				return $output;
			}else{
				// return '<h3 style="text-align:center;">'. __('Jesteś już zalogowany' , 'hide-calatogue').'</h3>';
				wp_redirect(home_url()); exit;
			}
		}

		/*
		** Registration form fields
		*/

		public function morad_registration_form_fields(){
			ob_start(); ?>	
			<h3 class="morad_header"><?php _e('Register New Account'); ?></h3>
	 
			<?php 
			// show any error messages after form submission
			$this->show_registration_errors(); ?>
	 
			<form id="morad_registration_form" class="morad_form" action="" method="POST">
				<fieldset>
					<p>
						<label for="morad_user_Login"><?php _e('Username'); ?></label>
						<input name="morad_user_login" id="morad_user_login" class="required" type="text"/>
					</p>
					<p>
						<label for="morad_user_email"><?php _e('Email'); ?></label>
						<input name="morad_user_email" id="morad_user_email" class="required" type="email"/>
					</p>
					<p>
						<label for="morad_user_first"><?php _e('First Name'); ?></label>
						<input name="morad_user_first" id="morad_user_first" type="text"/>
					</p>
					<p>
						<label for="morad_user_last"><?php _e('Last Name'); ?></label>
						<input name="morad_user_last" id="morad_user_last" type="text"/>
					</p>
					<p>
						<label for="password"><?php _e('Password'); ?></label>
						<input name="morad_user_pass" id="password" class="required" type="password"/>
					</p>
					<p>
						<label for="password_again"><?php _e('Password Again'); ?></label>
						<input name="morad_user_pass_confirm" id="password_again" class="required" type="password"/>
					</p>
					<p>
						<input type="hidden" name="morad_register_nonce" value="<?php echo wp_create_nonce('morad-register-nonce'); ?>"/>
						<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
					</p>
				</fieldset>
			</form>
		<?php
		return ob_get_clean();
		}

		/*
		** Display registration errors
		*/
		public function show_registration_errors(){
			if($codes = $this->morad_errors()->get_error_codes()) {
				echo '<div class="morad_errors">';
				    // Loop error codes and display errors
				   foreach($codes as $code){
				        $message = $this->morad_errors()->get_error_message($code);
				        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
				    }
				echo '</div>';
			}	
		}

		/*
		** Errors message
		*/
		public function morad_errors(){
		    static $wp_error; // Will hold global variable safely
		    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
		}

		/*
		** Save new user in database
		*/

		public function save_new_user(){

			if (isset( $_POST["morad_user_login"] ) && wp_verify_nonce($_POST['morad_register_nonce'], 'morad-register-nonce')) {
			      $user_login		= $_POST["morad_user_login"];	
			      $user_email		= $_POST["morad_user_email"];
			      $user_first 	    = $_POST["morad_user_first"];
			      $user_last	 	= $_POST["morad_user_last"];
			      $user_pass		= $_POST["morad_user_pass"];
			      $pass_confirm 	= $_POST["morad_user_pass_confirm"];
			      
			      // this is required for username checks
			      // require_once(ABSPATH . WPINC . '/registration.php');
			      
			      if(username_exists($user_login)) {
			          // Username already registered
			          $this->morad_errors()->add('username_unavailable', __('Username already taken'));
			      }
			      if(!validate_username($user_login)) {
			          // invalid username
			          $this->morad_errors()->add('username_invalid', __('Invalid username'));
			      }
			      if($user_login == '') {
			          // empty username
			          $this->morad_errors()->add('username_empty', __('Please enter a username'));
			      }
			      if(!is_email($user_email)) {
			          //invalid email
			          $this->morad_errors()->add('email_invalid', __('Invalid email'));
			      }
			      if(email_exists($user_email)) {
			          //Email address already registered
			          $this->morad_errors()->add('email_used', __('Email already registered'));
			      }
			      if($user_pass == '') {
			          // passwords do not match
			          $this->morad_errors()->add('password_empty', __('Please enter a password'));
			      }
			      if($user_pass != $pass_confirm) {
			          // passwords do not match
			          $this->morad_errors()->add('password_mismatch', __('Passwords do not match'));
			      }
			      
			      $errors = $this->morad_errors()->get_error_messages();
			      
			      // only create the user in if there are no errors
			      if(empty($errors)) {
			          
			          $new_user_id = wp_insert_user(
			          		array(
			                  'user_login'		=> $user_login,
			                  'user_pass'	 		=> $user_pass,
			                  'user_email'		=> $user_email,
			                  'first_name'		=> $user_first,
			                  'last_name'			=> $user_last,
			                  'user_registered'	=> date('Y-m-d H:i:s'),
			                  'role'				=> 'subscriber',
			                  'show_admin_bar_front' => 'false' ,
			                  'meta_input'      => array(
			                  	'client_role' => 'Odbiorcy systemów budowlanych' ,
			                  	'client_company_name' => 'WITMAR S.C.' ,
			                  	'client_nip'  => 53535353536 ,
			                  	'client_post_code'  =>  '38-124' ,
			                  	'client_city'     => 'Wańkowa' ,
			                  	'client_street'   => 'Lisa-Kuli 2c' ,
			                  	'client_voivodeship'   => 'podkarpackie' ,
			                  	'client_phone'   => '345353534534' ,
			                  	'client_newsletter'   => 0


			                  )
			               )
			          );
			          if($new_user_id) {
			              // send an email to the admin alerting them of the registration
			              wp_new_user_notification($new_user_id);
			              
			              // log the new user in
			              wp_setcookie($user_login, $user_pass, true);
			              wp_set_current_user($new_user_id, $user_login);	
			              do_action('wp_login', $user_login);
			              
			              // send the newly created user to the home page after logging them in
			              wp_redirect(home_url()); exit;
			          }
			          
			      }
			  
			}

		}


		/*
		** Add custom fields to user profile page
		*/

		public function custom_user_profile_fields($user)
		{
		    ?>
		    <h3>Niestandardowe Dane</h3>

		   <table class="form-table">
			    <tr>
			        <th><label for="client_role">Rola Klienta</label></th>
			        <td>
			            <input type="text" name="client_role" value="<?php echo esc_html(get_user_meta($user->ID, 'client_role', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_company_name">Nazwa Firmy</label></th>
			        <td>
			            <input type="text" name="client_company_name" value="<?php echo esc_html(get_user_meta($user->ID, 'client_company_name', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_nip">NIP</label></th>
			        <td>
			            <input type="text" name="client_nip" value="<?php echo esc_html(get_user_meta($user->ID, 'client_nip', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_post_code">Kod pocztowy</label></th>
			        <td>
			            <input type="text" name="client_post_code" value="<?php echo esc_html(get_user_meta($user->ID, 'client_post_code', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_city">Miasto</label></th>
			        <td>
			            <input type="text" name="client_city" value="<?php echo esc_html(get_user_meta($user->ID, 'client_city', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_street">Ulica</label></th>
			        <td>
			            <input type="text" name="client_street" value="<?php echo esc_html(get_user_meta($user->ID, 'client_street', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_voivodeship">Województwo</label></th>
			        <td>
			            <input type="text" name="client_voivodeship" value="<?php echo esc_html(get_user_meta($user->ID, 'client_voivodeship', true)); ?>">
			        </td>
			    </tr>
			    <tr>
			        <th><label for="client_phone">Telefon</label></th>
			        <td>
			            <input type="text" name="client_phone" value="<?php echo esc_html(get_user_meta($user->ID, 'client_phone', true)); ?>">
			        </td>
			    </tr>
			    <tr>
				    <th><label for="client_newsletter">Newsletter</label></th>
				    <td>
				        <select name="client_newsletter">
				            <option value="1" <?php selected(get_user_meta($user->ID, 'client_newsletter', true), 1); ?>>Tak</option>
				            <option value="0" <?php selected(get_user_meta($user->ID, 'client_newsletter', true), 0); ?>>Nie</option>
				        </select>
				    </td>
				</tr>
			</table>
		    <?php
		}


		/*
		** save_custom_user_profile_fields
		*/

		public function save_custom_user_profile_fields($user_id)
		{
		    // Sprawdź, czy bieżący użytkownik ma uprawnienia do edycji profili
		    if (!current_user_can('edit_user', $user_id)) {
		        return false;
		    }

		    // Pobierz wszystkie niestandardowe pola użytkownika
		    $custom_fields = array(
		        'client_role',
		        'client_company_name',
		        'client_nip',
		        'client_post_code',
		        'client_city',
		        'client_street',
		        'client_voivodeship',
		        'client_phone',
		        'client_newsletter'
		    );

		    // Iteruj przez wszystkie niestandardowe pola
		    foreach ($custom_fields as $field) {
		        // Sprawdź, czy dane zostały przesłane
		        if (isset($_POST[$field])) {
		            // Zaktualizuj wartość pola meta użytkownika
		            update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
		        }
		    }
		}










	}

}