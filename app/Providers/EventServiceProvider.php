<?php
	
	namespace App\Providers;
	
	use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
	
	class EventServiceProvider extends ServiceProvider
	{
		/**
		 * The event listener mappings for the application.
		 *
		 * @var array
		 */
		protected $listen = [
			'App\Events\Event' => [
				'App\Listeners\EventListener',
			],
			'App\Events\AddLeed' => [
				// Telegram notification for managers
				'App\Listeners\LeedTelegramNotification@handle',
				// Email notification for managers
				'App\Listeners\LeedEmailNotification@handle',
			],
			'App\Events\AddLeedPromo' => [
				// Email notification for clients
				'App\Listeners\LeedPromoUserEmailNotification@handle',
				// Email notification for managers
				'App\Listeners\LeedPromoEmailNotification@handle',
				// Telegram notification for managers
				'App\Listeners\LeedPromoTelegramNotification@handle',
			],
		];
		
		/**
		 * Register any events for your application.
		 *
		 * @return void
		 */
		public function boot () {
			parent::boot();
			//
		}
	}
