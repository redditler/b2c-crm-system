<?php
	
	namespace App\Listeners;
	
	use App\Events\AddLeedPromo;
	use App\Regions;
	use Illuminate\Support\Facades\Mail;
	use App\Mail\SendMailable;
	use App\User;
	
	class LeedPromoEmailNotification
	{
		protected $_region;
		
		/**
		 * LeedPromoEmailNotification constructor.
		 *
		 * @param Regions $regions
		 */
		public function __construct (Regions $regions) {
			$this->_region = $regions;
		}
		
		/**
		 * @param AddLeedPromo $event
		 */
		public function handle (AddLeedPromo $event) {
			$this->_sendEmailNotice($event);
		}
		
		/**
		 * @param $leed_data
		 */
		private function _sendEmailNotice ($leed_data) {
			$region_id = $leed_data->leed_request->leed_region_id;
			$promo_name = $leed_data->leed_request->leed_name;
			$promo_phone = $leed_data->leed_request->leed_phone;
			$promo_region = $this->_region->getRegion($region_id)->name;
			$promo_discount = $leed_data->leed_request->leed_promo_discount;
			$promo_code = $leed_data->leed_request->leed_promo_code;

			$emails = [];
			$name = 'ПРОМОКОД';
			$subject = 'Новый Промокод';
			$template = 'add-new-leed-promo';
			
			$data = [
				'promo_name' => $promo_name,
				'promo_phone' => $promo_phone,
				'promo_region' => $promo_region,
				'promo_discount' => $promo_discount,
				'promo_code' => $promo_code,
			];
			
			$emails = $this->_getUsersEmail($region_id);
			
			if (count($emails)) {
				foreach ($emails as $email) {
					if ( ! is_null($email)) {
						Mail::to($email)->send(new SendMailable($name, $subject, $template, $data));
					}
				}
			}
		}
		
		/**
		 * @param int $region_id
		 * @return array
		 */
		private function _getUsersEmail ($region_id) {
			$users = new User();
			return $users->getUsersEmailsByRegion($region_id);
		}
	}
