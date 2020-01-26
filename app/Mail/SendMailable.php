<?php
	
	namespace App\Mail;
	
	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Contracts\Queue\ShouldQueue;
	
	class SendMailable extends Mailable
	{
		use Queueable, SerializesModels;
		public $name;
		public $subject;
		public $template;
		public $data;
		
		/**
		 * SendMailable constructor.
		 *
		 * @param string $name
		 * @param string $subject
		 * @param        $template
		 * @param array  $data
		 */
		public function __construct ($name = 'New message', $subject = 'New message', $template, $data = []) {
			$this->name = $name;
			$this->subject = $subject;
			$this->template = $template;
			$this->data = $data;
		}
		
		/**
		 * Build the message.
		 *
		 * @return $this
		 */
		public function build () {
			return $this->from('no-reply@stekomail.com.ua', $this->name)
				->subject($this->subject)
				->view('mail/'.$this->template, $this->data);
		}
	}
