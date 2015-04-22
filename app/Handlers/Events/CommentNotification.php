<?php
namespace Valyria\Handlers\Events;

use Valyria\Events\Comment;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Mail;

class CommentNotification
{
	/**
	 * Create the event handler
	 */
	public function __construct() {}

	/**
	 * Handle the event.
	 *
	 * @param  Comment  $event
	 * @return void
	 */
	public function handle(Comment $event)
	{
		$comment = $event->getEntity();
		$message = 'Hello folk!'
				. ' You got a new comment on your image name:'
				. ' (' . $comment->image->name . '), enjoy it!';

		Mail::raw($message, function($message) use ($comment) {
			$image = $comment->image;
			$user  = $image->user;
			$name  = $user->first_name . ' ' . $user->last_name;
			$email = $user->email;

			$message->from('valyria@gocobachi.mx', 'Valyria API');
			$message->to($email, $name);
			$message->subject('New comment added');
		});
	}
}