<?php
add_action('admin_menu', 'avayo_menu');

function avayo_menu() {
	add_menu_page('Avayo', 'Avayo', 'administrator',  'avayo.php', 'avayo_settings_page', plugin_dir_url( __FILE__ ) . '../assets/img/icon.png' );

	add_action( 'admin_init', 'register_avayo_settings' );
}

function avayo_init($hook) {
    if($hook != 'toplevel_page_avayo') {
        return;
    }
	wp_enqueue_style('avayo_styles' , plugin_dir_url( __FILE__ ) . '../assets/css/style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . '/../assets/css/style.css' ) );
	wp_enqueue_script('avayo_script' , plugin_dir_url( __FILE__ ) . '../assets/js/main.js', array('jquery'), filemtime( plugin_dir_path( __FILE__ ) . '/../assets/js/main.js' ) );
}

add_action( 'admin_enqueue_scripts', 'avayo_init' );

function register_avayo_settings() {
	register_setting( 'avayo-settings', 'avayo_account' );
}

add_action('update_option_avayo_account', function( $old_value, $value ) {

	$url = 'https://admin.avayo.nl/api/v1/accounts/info/' . $value;
	$get = wp_remote_get($url);
	$resp = json_decode($get['body']);

	if( $resp->status == 'ok' ) {
		update_option('avayo_account_type', strtolower($resp->account->package) );
	} else {
		update_option('avayo_account_type', 'error' );
	}

}, 10, 2);

function avayo_settings_page() {
	$locales = array(
		'nl' => 'Nederlands',
		'fr' => 'Frans',
		'en' => 'Engels',
		'de' => 'Duits'
	);

    $account_type = get_option('avayo_account_type') ? : '';
?>
<div class="wrap">

	<h2><img src="<?php echo plugin_dir_url( __FILE__ ) . '../assets/img/avayo.png'; ?>" class="logo" alt="Avayo - Online ticketing software" title="Avayo - Online ticketing software" /></h2>

	<?php
		// set default value for avayo_account
		if(!get_option('avayo_account')){
			update_option('avayo_account', '');
		}

		if( $account_type == 'error' ) :
	?>
		<div class="error notice">
		    <p><?php _e('Uw account is niet correct ingevoerd.', 'avayo-plugin'); ?></p>
		</div>
	<?php
		endif;
	?>

	<div id="poststuff">
		<div class="postbox">
			<h2 class="hndle"><?php _e('Instellingen', 'avayo-plugin'); ?></h2>
			<div class="postbox__inner">
				<form method="post" action="options.php">
				    <?php settings_fields( 'avayo-settings' ); ?>
				    <?php do_settings_sections( 'avayo-settings' ); ?>
				    <table class="form-table">
				        <tr valign="top">
					        <th scope="row"><?php _e('Account', 'avayo-plugin'); ?></th>
					        <td>
						        <input type="text" name="avayo_account" value="<?php echo esc_attr( get_option('avayo_account') ); ?>" />
						    </td>
				        </tr>
				        <?php
					        if( $account_type != '' && $account_type != 'error' ) :
					    ?>
				        <tr valign="top">
					        <th scope="row"><?php _e('Uw pakket', 'avayo-plugin'); ?></th>
					        <td>
						        <?php echo ucfirst($account_type); ?>
						    </td>
				        </tr>
				        <?php
					        endif;
						?>
				    </table>
				    <?php
					    if( $account_type == '' || $account_type == 'error' ) :
					?>
				    <p class="howto"><?php _e('Uw account is het subdomein waar uw ticketshop op te vinden is bij avayo.<br/>Bijvoorbeeld in het geval van demo-orange.avayo.nl is de accountnaam "demo-orange"', 'avayo-plugin'); ?></p>
				    <?php
					    endif;
					?>
				    <?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php
			if( $account_type != '' && $account_type != 'error' ) :
		?>
		<div class="postbox">
			<h2 class="hndle">Avayo Shortcode</h2>
			<div class="postbox__inner">
			    <table class="form-table">
			        <?php
				        if( $account_type == 'red' ) :
				    ?>
			        <tr valign="top">
				        <th scope="row"><?php _e('Weergave', 'avayo-plugin'); ?></th>
				        <td>
						    <table class="form-table">
								<tr>
									<td>
										<select id="avayo_display_selector" name="avayo_display">
											<option value="shop">Ticketshop (standaard)</option>
											<option value="mini">Minicalender</option>
										</select>
										<p class="howto"><?php _e('Kies hier de gewenste weergave.', 'avayo-plugin'); ?></p>
									</td>
									<td id="mini_items_column" style="display:none">
					        			<input type="text" name="avayo_mini_items" value="4" />
					        			<p class="howto"><?php _e('Hoeveel items wil je laten zien?', 'avayo-plugin'); ?></p>
									</td>
								</tr>
							</table>

					    </td>
			        </tr>
			        <?php
				        endif;
				    ?>

			        <tr valign="top">
			        <?php
				        if( $account_type != 'red' ) :
				    ?>
				        <th scope="row"><?php _e('Taal', 'avayo-plugin'); ?></th>
				        <td>
					        <select name="avayo_locale">
						        <?php
							        foreach( $locales as $locale => $label ) :
							        	echo '<option value="' . $locale . '">' . $label . '</option>';
							        endforeach;
							    ?>
					        </select>
					        <p class="howto"><?php _e('Kies hier in welke taal u de ticketshop wilt weergeven. U dient deze taal wel in de ticketshop geactiveerd te hebben.', 'avayo-plugin'); ?></p>
					    </td>
			        </tr>
			        <tr>
			        <?php
				        endif;
				    ?>
				    <?php
					    if( 1 == 2 ) :
					?>
			        <?php
				        if( $account_type == 'orange' ) :
				    ?>
			        <tr valign="top">
				        <th scope="row"><?php _e('Toon header', 'avayo_plugin'); ?></th>
				        <td>
							<label>
								<input type="checkbox" name="avayo_show_banner" />
							</label>
					    </td>
			        </tr>
					<?php
						endif;
					?>
			        <tr valign="top">
				        <th scope="row"><?php _e('Event ID', 'avayo-plugin'); ?> <?php _e('(optioneel)', 'avayo-plugin'); ?></th>
				        <td>
					        <input type="text" name="avayo_event_id" value="" />
					    </td>
			        </tr>
			        <?php
				        if( $account_type == 'red' ) :
				    ?>
			        <tr valign="top">
				        <th scope="row"><?php _e('Performance ID', 'avayo-plugin'); ?> <?php _e('(optioneel)', 'avayo-plugin'); ?></th>
				        <td>
					        <input type="text" name="avayo_p_id" value="" />
					    </td>
			        </tr>
			        <?php
				        endif;
				    ?>
				    <?php
					    endif;
					?>
			        <tr valign="top">
				        <th scope="row">Shortcode</th>
				        <td>
					        <input type="text" id="shortcode" value="[avayo]" />
					        <span class="button button--copy button--secondary"><?php _e('Kopiëren', 'avayo-plugin'); ?></span>
					        <p class="howto"><?php _e('Kopieer deze shortcode en plak hem in de editor op de plek waar u de ticketshop weer wilt geven', 'avayo-plugin'); ?></p><br/>
	    				    <p class="howto"><?php echo sprintf( __('Komt u er niet uit? &nbsp; <a href="%s" class="button button-secondary" target="_blank">Klik dan hier</a> om een handleiding te lezen over het gebruik van shortcodes.', 'avayo_plugin'), __('http://www.wpbeginner.com/glossary/shortcodes/', 'avayo-plugin')); ?></p>
					    </td>
			        </tr>
			    </table>
			</div>
		</div>
		<?php
			endif;
		?>
	</div>
</div>
<p class="howto"><?php _e('Voor vragen of opmerkingen kunt u ons bereiken via <a href="mailto:support@avayo.nl">support@avayo.nl</a>', 'avayo-plugin'); ?></p>
<?php
	}
?>
