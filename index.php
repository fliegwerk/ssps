<?php
require 'config.php';
require 'app/app.php';

use ServerStatusMonitor\Message;
use ServerStatusMonitor\Service;

$services = Service::get();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?> Service Status</title>
    <meta name="description" content="An overview of the status of the different services provided by <?= $title ?>">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        :root {
            --font: <?=$font?>;
            --green: #00ff00;
            --orange: #ffff00;
            --red: #ff0000;
            --blue: #0000ff;
        }
    </style>
</head>
<body>
<header>
    <img id="Logo" src="<?= $logo_url ?>" alt="Logo <?= $title ?>">
    <time id="Clock"><?= date('Y-m-d H:i:s') ?> UTC</time>
</header>
<h1><?= $title ?> Services Status</h1>
<? foreach ($services as $service): ?>
    <article>
        <span class="<?= $dots ? 'Dot' : '' ?> <?= $service->getState()->color ?>">&nbsp;</span>
        <main>
            <header>
                <h2><?= $service->title ?>:</h2>
				<?= $service->getState()->title ?>
            </header>
			<? foreach ($service->getMessages() as $message) {
				Message::render($message);
			} ?>
        </main>
    </article>
<? endforeach; ?>
<hr>
<footer>
    <div>
        <a href="feed.php" target="_blank" rel="noopener">Subscribe to RSS Feed</a>
        &nbsp;&nbsp;
		<? if ($admin_link): ?>
            <a href="admin.php" target="_blank" rel="noopener,nofollow">Admin Panel</a>
		<? endif; ?>
        <br>
        Powered by the <a href="https://github.com/fliegwerk/ssps" target="_blank" rel="noopener">
            fliegwerk Service Status Page System
        </a>
    </div>
</footer>
</body>
</html>
