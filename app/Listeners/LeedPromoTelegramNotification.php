<?php
	
	namespace App\Listeners;
	
	use App\Events\AddLeedPromo;
	use App\Http\Controllers\TelegramController;
	use App\Regions;
	use App\User;
	
	class LeedPromoTelegramNotification
	{
		/**
		 * @var User
		 */
		private $_user;
		/**
		 * @var Regions
		 */
		private $_region;
		
		/**
		 * LeedPromoTelegramNotification constructor.
		 *
		 * @param User    $user
		 * @param Regions $regions
		 */
		public function __construct (User $user, Regions $regions) {
			$this->_user = $user;
			$this->_region = $regions;
		}
		
		/**
		 * Handle the event.
		 *
		 * @param  AddLeedPromo $event
		 * @return void
		 */
		public function handle (AddLeedPromo $event) {
			$region_id = $event->leed_request->leed_region_id;
			
			$promo_name = $event->leed_request->leed_name;
			$promo_phone = $event->leed_request->leed_phone;
			$promo_region = $this->_region->getRegion($region_id)->name;
			$promo_discount = $event->leed_request->leed_promo_discount;
			$promo_code = $event->leed_request->leed_promo_code;
			
			$users_ids = $this->_user->getUsersTelegramByRegion($region_id);
			
			$rn = "\r\n";
			$msg = 'Новая заявка'.$rn;
			$msg .= 'От: <b>'.$promo_name.'</b>'.$rn;
			$msg .= 'Номер телефона: <b>'.$promo_phone.'</b>'.$rn;
			$msg .= 'Город: <b>'.$promo_region.'</b>'.$rn;
			$msg .= 'Размер скидки: <b>'.$promo_discount.'</b>'.$rn;
			$msg .= 'Промокод: <b>'.$promo_code.'</b>'.$rn;
			
			$this->_sendMsg($users_ids, $msg);
		}
		
		/**
		 * @param array  $telegram_ids
		 * @param string $msg
		 */
		private function _sendMsg (array $telegram_ids, $msg) {
			if (count($telegram_ids)) {
				foreach ($telegram_ids as $t_id) {
					TelegramController::send($t_id, $msg);
				}
			}
		}
	}
