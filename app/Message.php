<?php


namespace ServerStatusMonitor;

use DateTime;
use Exception;

class Message
{
	static function render($entry) {
	    $data = Data::get();
	    $stateKey = $entry->state;
	    $state = $data->states->$stateKey;
		try {
			$date = new DateTime($entry->date);
		} catch (Exception $e) {
			echo('<h3>Invalid date format</h3>');
		}
		?>
		<h3>
			<time><?=date_format($date, 'Y-m-d H:i:s')?> UTC</time>
		</h3>
		<p>
            <i><?=$state->title?></i> &ndash;
			<?=$entry->message?>
		</p>
		<?php
	}
}