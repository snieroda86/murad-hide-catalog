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

			add_filter( 'manage_users_columns',  array( $this , 'add_newsletter_column_to_table' ) );

			add_filter('manage_users_custom_column', array( $this , 'add_newsletter_value_to_column' ) , 10, 3 );
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
			wp_enqueue_script('morad-webpro14-js');
			wp_enqueue_style('morad-webpro14-css');
			$login_url = wp_login_url();

			ob_start(); ?>	
			<h3 class="morad_header"><?php _e('Zarejestruj się'); ?> lub <a href="<?php echo $login_url ?>"><u>zaloguj</u></a></h3>
	 
			<?php 
			// show any error messages after form submission
			$this->show_registration_errors(); ?>
	 
			<form id="morad_registration_form" class="morad_form" action="" method="POST">
				<fieldset>
					<h5><?php _e('Wybór grupy docelowej'); ?></h5>
					<p>
						
						<div>
							<label  style="display: block;">
								<input type="radio" value="Odbiorcy systemów budowlanych" name="client_role" checked>
								Odbiorcy systemów budowlanych
							</label>

							<label  style="display: block;">
								<input type="radio" value="Montażyści" name="client_role">
								Montażyści
							</label>

							<label  style="display: block;">
								<input type="radio" value="Architekci" name="client_role">
								Architekci
							</label>

							<label  style="display: block;">
								<input type="radio" value="Inne" name="client_role">
								Inne
							</label>
							
						</div>
					</p>

					<h5><?php _e('Dane użytkownika'); ?></h5>
					<p>
						<label for="morad_user_first"><?php _e('Imię'); ?></label>
						<input name="morad_user_first" id="morad_user_first" required type="text"/>
					</p>
					<p>
						<label for="morad_user_last"><?php _e('Nazwisko'); ?></label>
						<input name="morad_user_last" id="morad_user_last" required type="text"/>
					</p>

					<p>
						<label for="morad_user_login"><?php _e('Nazwa użytkownika'); ?></label>
						<input name="morad_user_login" id="morad_user_login" class="required" required type="text"/>
					</p>

					<p>
						<label for="morad_user_company"><?php _e('Nazwa firmy'); ?></label>
						<input name="morad_user_company" id="morad_user_company" class="required" required type="text"/>
					</p>

					<p>
						<label for="morad_user_nip"><?php _e('NIP'); ?></label>
						<input name="morad_user_nip" id="morad_user_nip" class="required" required minlength="10" maxlength="10" type="text"/>
					</p>
					
					<p>
						<label for="morad_user_email"><?php _e('Email'); ?></label>
						<input name="morad_user_email" id="morad_user_email" class="required" required type="email"/>
					</p>
					
					
					<p>
						<label for="morad_user_pass"><?php _e('Hasło'); ?></label>
						<input name="morad_user_pass" id="morad_user_pass" class="required" required type="password"/>
					</p>
					<p>
						<label for="password_again"><?php _e('Powtórz hasło'); ?></label>
						<input name="morad_user_pass_confirm" id="password_again" class="required" required type="password"/>
					</p>

					<h5><?php _e('Dane teleadresowe'); ?></h5>

					<p>
						<label for="morad_user_postcode"><?php _e('Kod pocztowy'); ?></label>
						<input name="morad_user_postcode" id="morad_user_postcode" class="required" required type="text"/>
					</p>
					<p>
						<label for="morad_user_city"><?php _e('Miasto'); ?></label>
						<input name="morad_user_city" id="morad_user_city" class="required" required type="text"/>
					</p>
					<p>
						<label for="morad_user_street"><?php _e('Ulica'); ?></label>
						<input name="morad_user_street" id="morad_user_street" class="required" required type="text"/>
					</p>
					<p>
						<label for="wojewodztwo">Województwo</label>
						<select id="wojewodztwo" name="morad_user_voivodeship" required>
						    <option value="" disabled selected>-- wybierz --</option>
						    <option value="dolnoslaskie">Dolnośląskie</option>
						    <option value="kujawsko-pomorskie">Kujawsko-Pomorskie</option>
						    <option value="lubelskie">Lubelskie</option>
						    <option value="lubuskie">Lubuskie</option>
						    <option value="lodzkie">Łódzkie</option>
						    <option value="malopolskie">Małopolskie</option>
						    <option value="mazowieckie">Mazowieckie</option>
						    <option value="opolskie">Opolskie</option>
						    <option value="podkarpackie">Podkarpackie</option>
						    <option value="podlaskie">Podlaskie</option>
						    <option value="pomorskie">Pomorskie</option>
						    <option value="slaskie">Śląskie</option>
						    <option value="swietokrzyskie">Świętokrzyskie</option>
						    <option value="warminsko-mazurskie">Warmińsko-Mazurskie</option>
						    <option value="wielkopolskie">Wielkopolskie</option>
						    <option value="zachodniopomorskie">Zachodniopomorskie</option>
						</select>
					</p>

					<p>
						<label for="morad_user_phone"><?php _e('Telefon'); ?></label>
						<input name="morad_user_phone" id="morad_user_phone" class="required" required type="tel"/>
					</p>


					<h5><?php _e('Wyrażanie zgody'); ?></h5>

					<p>
						<label><input type="checkbox"  class="consent-field-sn" id="register-check-all">
						Zaznacz wszystkie</label>
					</p>

					<p>
						<label><input type="checkbox" class="consent-field-sn"  required>
						Akceptuję <a terget="_blank" href="<?php echo site_url()?>/regulamin">regulamin</a></label>
					</p>

					<p>
						<label><input type="checkbox" class="consent-field-sn"  required>
						Zapoznałem się z informacją o przetwarzaniu danych</label>
					</p>

					<p>
						<label><input type="checkbox" class="consent-field-sn"  name="morad_user_newsletter">
						Chcę otrzymywać newslettery informacyjne Morad. Rekomendowane przez MORAD dla użytkowników strefy autoryzowanej.</label>
					</p>



					<p>
						<input type="hidden" name="morad_register_nonce" value="<?php echo wp_create_nonce('morad-register-nonce'); ?>"/>
						<input type="submit" value="<?php _e('Zarejestruj się'); ?>"/>
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
				        echo '<span class="error"><strong>' . __('Błąd')  . '</strong>: ' . $message . '</span><br/>';
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
			     

					$client_role = $_POST['client_role'];
				    $morad_user_first = $_POST['morad_user_first'];
				    $morad_user_last = $_POST['morad_user_last'];
				    $morad_user_login = $_POST['morad_user_login'];
				    $morad_user_company = $_POST['morad_user_company'];
				    $morad_user_nip = $_POST['morad_user_nip'];
				    $morad_user_email = $_POST['morad_user_email'];
				    $morad_user_pass = $_POST['morad_user_pass'];
				    $morad_user_pass_confirm 	= $_POST["morad_user_pass_confirm"];
				    $morad_user_postcode = $_POST['morad_user_postcode'];
				    $morad_user_city = $_POST['morad_user_city'];
				    $morad_user_street = $_POST['morad_user_street'];
				    $morad_user_voivodeship = $_POST['morad_user_voivodeship'];
				    $morad_user_phone = $_POST['morad_user_phone'];
				    $morad_user_newsletter = isset($_POST['morad_user_newsletter']) ? 1 : 0;




			      
			      // this is required for username checks
			      // require_once(ABSPATH . WPINC . '/registration.php');


				  if($client_role == '') {
			          
			          $this->morad_errors()->add('password_client_role', __('Wybierz rolę'));
			      }

			      if($morad_user_first == '') {
			          
			          $this->morad_errors()->add('morad_user_first_empty', __('Wpisz imię'));
			      }

			      if($morad_user_last == '') {
			          
			          $this->morad_errors()->add('morad_user_last_empty', __('Wpisz nazwisko'));
			      }

			      if($morad_user_company == '') {
			          
			          $this->morad_errors()->add('morad_user_company_empty', __('Wpisz nazwę firmy'));
			      }

			      if($morad_user_nip == '') {
			          
			          $this->morad_errors()->add('morad_user_nip_empty', __('Wpisz numer NIP'));
			      }

			      if($morad_user_postcode == '') {
			          
			          $this->morad_errors()->add('morad_user_postcode_empty', __('Wpisz kod pocztowy'));
			      }

			      if($morad_user_city == '') {
			          
			          $this->morad_errors()->add('morad_user_city_empty', __('Wpisz miasto'));
			      }

			      if($morad_user_street == '') {
			          
			          $this->morad_errors()->add('morad_user_street_empty', __('Wpisz ulicę'));
			      }

			       if($morad_user_voivodeship == '') {
			          
			          $this->morad_errors()->add('morad_user_voivodeship_empty', __('Wybierz województwo'));
			      }

			       if($morad_user_phone == '') {
			          
			          $this->morad_errors()->add('morad_user_phone_empty', __('Wpisz numer telefonu'));
			      }





			      
			      if(username_exists($morad_user_login)) {
			          // Username already registered
			          $this->morad_errors()->add('username_unavailable', __('Taki użytkownik już istnieje'));
			      }
			      if(!validate_username($morad_user_login)) {
			          // invalid username
			          $this->morad_errors()->add('username_invalid', __('Niepoprawna nazwa użytkownika'));
			      }
			      if($morad_user_login == '') {
			          // empty username
			          $this->morad_errors()->add('username_empty', __('Wpisz nazwę użytkownika'));
			      }
			      if(!is_email($morad_user_email)) {
			          //invalid email
			          $this->morad_errors()->add('email_invalid', __('Niepoprawny email'));
			      }
			      if(email_exists($morad_user_email)) {
			          //Email address already registered
			          $this->morad_errors()->add('email_used', __('Email już istnieje'));
			      }
			      if($morad_user_pass == '') {
			          // passwords do not match
			          $this->morad_errors()->add('password_empty', __('Wpisz hasło'));
			      }
			      if($morad_user_pass != $morad_user_pass_confirm) {
			          // passwords do not match
			          $this->morad_errors()->add('password_mismatch', __('Hasła różnią się'));
			      }
			      
			      $errors = $this->morad_errors()->get_error_messages();
			      
			      // only create the user in if there are no errors
			      if(empty($errors)) {
			          
			          $new_user_id = wp_insert_user(
			          		array(
			                  'user_login'		=> $morad_user_login,
			                  'user_pass'	 		=> $morad_user_pass,
			                  'user_email'		=> $morad_user_email,
			                  'first_name'		=> $morad_user_first,
			                  'last_name'			=> $morad_user_last,
			                  'user_registered'	=> date('Y-m-d H:i:s'),
			                  'role'				=> 'subscriber',
			                  'show_admin_bar_front' => 'false' ,
			                  'meta_input'      => array(
			                  	'client_role' => $client_role ,
			                  	'client_company_name' => $morad_user_company ,
			                  	'client_nip'  => $morad_user_nip ,
			                  	'client_post_code'  =>  $morad_user_postcode ,
			                  	'client_city'     => $morad_user_city ,
			                  	'client_street'   => $morad_user_street ,
			                  	'client_voivodeship'   => $morad_user_voivodeship ,
			                  	'client_phone'   => $morad_user_phone ,
			                  	'client_newsletter'   => $morad_user_newsletter ,
			                  	'client_priviliges' => 'panel_allow_access'


			                  )
			               )
			          );
			          if($new_user_id) {
			              // send an email to the admin alerting them of the registration
			              wp_new_user_notification($new_user_id);
			              
			              // log the new user in
			              wp_setcookie($morad_user_login, $morad_user_pass, true);
			              wp_set_current_user($new_user_id, $morad_user_login);	
			              do_action('wp_login', $morad_user_login);
			              
			              // send the newly created user to the download files panel
			              $user_panel_url = get_permalink(10478);
			              wp_redirect($user_panel_url); exit;
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


		/*
		** Add newstetter column to users table in admin panel
		*/

		public function add_newsletter_column_to_table( $column ){
			$column['client_newsletter'] = 'Newsletter'; 
   			return $column;
		}


		public function add_newsletter_value_to_column( $val, $column_name, $user_id ) {
		  switch ($column_name) {
		    case 'client_newsletter' :
		     $newsletter_allow_number =  get_the_author_meta( 'client_newsletter', $user_id );
		     $newsletter_allow = '';
		     if($newsletter_allow_number == 1 ){
		     	$newsletter_allow = 'TAK';
		     }else{
		     	$newsletter_allow = 'NIE';
		     } 
		     return $newsletter_allow;
		     break;
		    default:
		  }
		 return $val;
		}










	}

}