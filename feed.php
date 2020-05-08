<?php

use ServerStatusMonitor\Data;

require 'config.php';
require 'app/app.php';

$data = Data::get();

header("Content-type: application/atom+xml");
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<rss version="2.0">
    <channel>
        <title><?= $title ?> Status</title>
        <description>Service Status announcements for the <?= $title ?> services</description>
        <language>en</language>
        <link><?= 'https://' . $_SERVER['HTTP_HOST'] ?></link>
        <lastBuildDate><?= date(DATE_RFC822) ?></lastBuildDate>

		<?php foreach (array_reverse($data->entries) as $index => $entry):
			$stateKey = $entry->state;
			$state = $data->states->$stateKey->title;
			$services = array_map(function ($serviceKey) use ($data) {
				return $data->services->$serviceKey;
			}, $entry->services)
			?>
            <item>
                <title><?= $state ?></title>
                <description>
                    State: <?= $state ?>
                    Affected Services: <?= implode(', ', $services) ?>
					<?= $entry->message ?>
                </description>
                <pubDate><?= date_format(new DateTime($entry->date), DATE_RFC822) ?></pubDate>
                <guid isPermaLink="false"><?= $entry->date ?></guid>
            </item>
		<?php endforeach; ?>
    </channel>
</rss>