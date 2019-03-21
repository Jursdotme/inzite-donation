<?php

use Kameli\Quickpay\Quickpay;

// Add Shortcode
function df_donation_func( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'merchantid' => '1',
			'agreement_id' => '811a23f235617fd534dd8cb1a3e84d32c74df92786d1fe8ad51eb7dea177a4f5',
			'continue_url' => '',
			'cancelurl' => '',
			'callbackurl' => ''
		),
		$atts
	);

	$shortcode_vars = array(
		'merchant_id' => $atts['merchantid'],
		'agreement_id' => $atts['agreement_id'],
		'continue_url' => '',
		'cancelurl' => '',
		'callbackurl' => ''
	);
	wp_localize_script( 'donation-script', 'donation_vars', $shortcode_vars );

	wp_enqueue_style( 'bulma');
  wp_enqueue_style( 'donation-styles');


	wp_enqueue_script( 'crypto-js' );
	wp_enqueue_script( 'vue-js' );
	wp_enqueue_script( 'donation-script' );
	ob_start(); 
		
		if ($_POST) :

			$order_data = $_POST;
			// echo '<pre>';
			// 	var_dump($order_data['continueurl']);
			// echo '</pre>';

			$order_prefix = "donation";
			$orderid = $order_prefix . time();
			$order_amount = $order_data['amount'] * 10;

			$qp = new Quickpay('811a23f235617fd534dd8cb1a3e84d32c74df92786d1fe8ad51eb7dea177a4f5', 'a81146b749bda508a4ca7f73f6d370608d04bda69ca162789696b5dd2168c8c3');
			$payment = $qp->payments()->create([
					'currency' => 'DKK',
					'order_id' => $orderid,
					'invoice_address' => [
						'name' => $order_data['invoice_address']['name'],
						'street' => $order_data['invoice_address']['street'],
						'zip_code' => $order_data['invoice_address']['zip_code'],
						'city' => $order_data['invoice_address']['city'],
						'email' => $order_data['invoice_address']['email'],
					],
					'variables' => [
						'Modtag Nyhedsbrev' => $order_data['subscibe_to_newsletter'],
						'terms_accepted' => $order_data['terms_accepted'],
						'gdpr_accepted' => $order_data['gdpr_accepted']
					],
					"continueurl" => "https://testdomain.dk/ordregennemfoert",
					"cancelurl" => $order_data['cancelurl'],
					"callbackurl" => $order_data['callbackurl'],
					'language' => 'Danish'

			]);

			$link = $qp->payments()->link($payment->getId(), [
					'amount' => $order_amount, // amount in least valuable unit (øre)
			]);

			// Make the user follow the payment link which will take them to a form where they put in their card details
			$url = $link->getUrl();

			// When the form has been completed, a POST request will be sent to a specified url where you can validate it
			if ($qp->validateCallback()) {
					$payment = $qp->receivePaymentCallback();

					// Capture the amount to charge the card
					$qp->payments()->captureAmount($payment->getId(), $payment->amount());

					// Handle order
			}

			echo '<div class="bu-columns" id="app">'.
			'<div class="bu-column">'.
			'<section class="bu-section">';
			
			echo '<dl>';

			echo '<dt>Navn:</dt>';
			echo '<dd>' . $order_data['invoice_address']['name'] . '</dd>';

			echo '<dt>Adresse:</dt>';
			echo '<dd>' . $order_data['invoice_address']['street'] . '</br>'.$order_data['invoice_address']['zip_code'].' '.$order_data['invoice_address']['city'].'</dd>';
			echo '<dt>Email:</dt>';
			echo '<dd>' . $order_data['invoice_address']['email'] . '</dd>';
			echo '<dt>Beløb:</dt>';
			echo '<dd>DKK ' . $order_data['amount'] / 10 . ',-</dd>';

			echo '<a href="'.$url.'" class="bu-button bu-is-success bu-is-large">Gå til betaling</a>';

			echo '</div></div></div>';
			
		else:
	?>
	<div class="bu-columns" id="app">
		<div class="bu-column">
			<section class="bu-section">
				<h1 class="bu-title">Giv et bidrag til os.</h1>
				<p class="bu-subtitle">
				Vi er taknemmelige for ethvert bidrag til at styrke vores kamp for at godt og trygt Danmark. Om det er 50,- kr. 100,- kr. eller mere, så er det tilsammen med til, at vi kan stå endnu stærkere, og vi siger tusind tak for din støtte.
				</p>
				<hr>

				<!-- form starts here -->
				<section class="bu-form">
					<form @submit="checkForm" method="POST" action="#">
						<input type="hidden" name="version" value="v10">
						<input type="hidden" name="merchant_id" value="1">
						<input type="hidden" name="agreement_id" value="1">
						<input type="hidden" name="order_id" value="0001">
						<input type="hidden" name="amount" v-bind:value="amount * 10">
						<input type="hidden" name="currency" value="DKK">
						<input type="hidden" name="continueurl" v-bind:value="continueurl">
						<input type="hidden" name="cancelurl" v-bind:value="cancelurl">
						<input type="hidden" name="callbackurl" v-bind:value="callbackurl">
						<input type="hidden" name="checksum" value="ed93f788f699c42aefa8a6713794b4d347ff493ecce1aca660581fb1511a1816">
						
						<input type="hidden" name="invoice_address[name]" v-bind:value="invoice_address.name">
						<input type="hidden" name="invoice_address[street]" v-bind:value="invoice_address.street">
						<input type="hidden" name="invoice_address[zip_code]" v-bind:value="invoice_address.zip_code">
						<input type="hidden" name="invoice_address[city]" v-bind:value="invoice_address.city">
						<input type="hidden" name="invoice_address[email]" v-bind:value="invoice_address.email">
						
						<input type="hidden" name="subscibe_to_newsletter" v-bind:value="subscibe_to_newsletter">
						<input type="hidden" name="terms_accepted" v-bind:value="terms_accepted">
						<input type="hidden" name="gdpr_accepted" v-bind:value="gdpr_accepted">
						<fieldset>
							<label class="bu-label bu-is-large">Jeg ønsker at donere</label>
							<div class="bu-field bu-has-addons">
								<div class="bu-control is-expanded">
									<input v-model="amount" class="bu-input bu-is-large" type="text" placeholder="0">
								</div>
								<div class="bu-control">
									<a class="bu-button is-static bu-is-large">kr.</a>
								</div>
							</div>
						</fieldset>

						<fieldset>
							<div class="bu-field">
								<label class="bu-label">Navn</label>
								<div class="bu-control">
									<input class="bu-input" type="text" v-model="invoice_address.name">
								</div>
							</div>

							<div class="bu-field">
								<label class="bu-label">Adresse</label>
								<div class="bu-control">
									<input class="bu-input" type="text" v-model="invoice_address.street">
								</div>
							</div>

							<div class="bu-field bu-is-grouped">

								<div class="bu-control">
									<label class="bu-label">Post nr.</label>

									<input class="bu-input" type="text" v-model="invoice_address.zip_code">
								</div>

								<div class="bu-control bu-is-expanded">
									<label class="bu-label">By</label>
									<input class="bu-input" type="text" v-model="invoice_address.city">
								</div>

							</div>

							<div class="bu-field">
								<label class="bu-label">Email</label>
								<div class="bu-control ">
									<input class="bu-input" type="email" value="" v-model="invoice_address.email">
								</div>
							</div>
						</fieldset>

						<fieldset>
							<div class="bu-field">
								<label class="bu-checkbox">
									<input type="checkbox" v-model="subscibe_to_newsletter">
									Jeg ønsker at modtage vores Nyhedsbrev.
								</label>
							</div>
							<div class="bu-field">
								<label class="bu-checkbox">
									<input type="checkbox" v-model="terms_accepted">
									Jeg accepterer <a href="#">handelsbetingelserne</a>.
								</label>
							</div>
							<div class="bu-field">
								<label class="bu-checkbox">
								<input type="checkbox" v-model="gdpr_accepted">
								Jeg har læst og accepterer vilkår for  <a href="#">behandling af personoplysninger</a>.
							</label>
							</div>
							
							<p><em>Vi gør opmærksom på at navn på bidrag ikke offentliggøres op til og med 20.900,- kr. indenfor kalenderåret. Bidrag over 20.900,- offentliggøres i regnskab med oplysninger om, hvem bidragsyder er.</em></p>

						</fieldset>

						<div class="bu-notification bu-is-warning" v-if="errors.length">
							<b>Ret venligst følgende fejl:</b>
							<ul>
								<li v-for="error in errors">{{ error }}</li>
							</ul>
						</div>
						
						<div class="bu-field">
							<div class="bu-control">
								<input type="submit" value="Næste" class="bu-button is-success">
							</div>
						</div>

						

					</form>
					
				</section>
			</section>
		</div>
	</div>

	<?php

		endif;

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'df_donation', 'df_donation_func' );