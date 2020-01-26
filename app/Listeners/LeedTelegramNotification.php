<?php
	
	namespace App\Listeners;
	
	use App\Events\AddLeed;
	use App\Http\Controllers\TelegramController;
	use App\LeedLabel;
	use App\Regions;
	use App\User;
	
	class LeedTelegramNotification
	{
		/**
		 * @var User
		 */
		private $_user;
		/**
		 * @var Regions
		 */
		private $_region;
		
		private $_label;
		
		/**
		 * LeedTelegramNotification constructor.
		 *
		 * @param User      $user
		 * @param Regions   $regions
		 * @param LeedLabel $leedLabel
		 */
		public function __construct (User $user, Regions $regions, LeedLabel $leedLabel) {
			$this->_user = $user;
			$this->_region = $regions;
			$this->_label = $leedLabel;
		}
		
		/**
		 * Handle the event.
		 *
		 * @param  AddLeed $event
		 * @return void
		 */
		public function handle (AddLeed $event) {
			$region_id = $event->leed_request->leed_region_id;
			$label_id = $event->leed_request->label_id;
			
			$leed_name = $event->leed_request->leed_name;
			$leed_phone = $event->leed_request->leed_phone;
			$leed_region = $this->_region->getRegion($region_id)->name;
			$leed_label = $this->_label->getLabels($label_id)->name;
			
			$users_ids = $this->_user->getUsersTelegramByRegion($region_id);
			
			$rn = "\r\n";
			$msg = 'Новая заявка'.$rn;
			$msg .= 'От: <b>'.$leed_name.'</b>'.$rn;
			$msg .= 'Номер телефона: <b>'.$leed_phone.'</b>'.$rn;
			$msg .= 'Город: <b>'.$leed_region.'</b>'.$rn;
			$msg .= 'Метка: <b>'.$leed_label.'</b>'.$rn;
			
			$this->_sendMsg($users_ids, $msg);
		}
		
		/**
		 * @param array  $telegram_ids
		 * @param string $msg
		 */
		private function _sendMsg (array $telegram_ids, string $msg) {
			if (count($telegram_ids)) {
				foreach ($telegram_ids as $t_id) {
					TelegramController::send($t_id, $msg);
				}
			}
		}
	}
