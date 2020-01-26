<?php
	
	namespace App\Listeners;
	
	use App\Events\AddLeedPromo;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Support\Facades\Mail;
	use App\Mail\SendMailable;
	
	class LeedPromoUserEmailNotification
	{
		/**
		 * LeedPromoUserEmailNotification constructor.
		 */
		public function __construct () {
			//
		}
		
		/**
		 * Handle the event.
		 *
		 * @param  AddLeedPromo $event
		 * @return void
		 */
		public function handle (AddLeedPromo $event) {
			$client_email = $event->leed_request->leed_email;
			$client_promo_code = $event->leed_request->leed_promo_code;
			$client_promo_discount = $event->leed_request->leed_promo_discount;
			
			if ($this->_checkEmail($client_email) === TRUE) {
				$name = 'Ваша знижка на вікна Steko';
				$subject = 'Від Steko';
				$template = 'leed-promo-user-notification';
				
				$data = [
					'promo_code' => $client_promo_code,
					'promo_discount' => $client_promo_discount,
				];
				
				Mail::to($client_email)->send(new SendMailable($name, $subject, $template, $data));
			}
		}
		
		/**
		 * Minimal email validation
		 *
		 * @param $email
		 * @return bool
		 */
		private function _checkEmail ($email) {
			if (is_string($email) && strlen($email) > 0) {
				$at_pos = strpos($email, '@');
				if ($at_pos !== FALSE) {
					return TRUE;
				}
			}
			return FALSE;
		}
	}
