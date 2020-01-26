<?php

	namespace App\Events;

	use Illuminate\Broadcasting\Channel;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Broadcasting\PresenceChannel;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

	class AddLeedPromo
	{
		use Dispatchable, InteractsWithSockets, SerializesModels;
		public $leed_request;

		/**
		 * AddLeedPromo constructor.
		 *
		 * @param $leed_request
		 */
		public function __construct ($leed_request) {
			$this->leed_request = $leed_request;
		}

		/**
		 * Get the channels the event should broadcast on.
		 *
		 * @return Channel|array
		 */
		public function broadcastOn () {
			return new PrivateChannel('channel-name');
		}
	}
