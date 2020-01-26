<?php
	
	namespace App\Listeners;
	
	use App\Events\AddLeed;
	use App\LeedLabel;
	use App\Regions;
	use Illuminate\Support\Facades\Mail;
	use App\Mail\SendMailable;
	use App\User;
	
	class LeedEmailNotification
	{
		protected $_region;
		
		/**
		 * LeedEmailNotification constructor.
		 *
		 * @param Regions   $regions
		 * @param LeedLabel $leedLabel
		 */
		public function __construct (Regions $regions, LeedLabel $leedLabel) {
			$this->_region = $regions;
			$this->_label = $leedLabel;
		}
		
		/**
		 * @param AddLeed $event
		 */
		public function handle (AddLeed $event) {
			$this->_sendEmailNotice($event);
		}
		
		/**
		 * @param $leed_data
		 */
		private function _sendEmailNotice ($leed_data) {
			$region_id = $leed_data->leed_request->leed_region_id;
			$label_id = $leed_data->leed_request->label_id;
			
			$leed_name = $leed_data->leed_request->leed_name;
			$leed_phone = $leed_data->leed_request->leed_phone;
			$leed_region = $this->_region->getRegion($region_id)->name;
			$leed_label = $this->_label->getLabels($label_id)->name;
			
			$emails = [];
			$name = 'Новая заявка';
			$subject = 'Новый ЛИД';
			$template = 'add-new-leed';
			
			$data = [
				'leed_name' => $leed_name,
				'leed_phone' => $leed_phone,
				'leed_region' => $leed_region,
				'leed_label' => $leed_label,
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
