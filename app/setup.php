<?php
/**
 * Render setup instructions and `die()`. Upon submission, handle the submission, save the `data.json` and let the program
 * continue.
 */

use ServerStatusMonitor\Data;

$validSetup = isset($_POST['password']);
$validSetup &= !empty($_POST['password']);
$validSetup &= isset($_POST['services']);
$validSetup &= !empty($_POST['services']);

if (!$validSetup):
	?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=yes, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>fliegwerk SSMS Setup</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
    <h1>
        fliegwerk SSMS Setup
    </h1>
    <article>
        <div>
            <p>
                You can find the instructions <a href="https://github.com/fliegwerk/ssps#Readme" target="_blank"
                                                 rel="noopener">
                    in the Project's README
                </a>.
            </p><br>
            <h2>Configuration</h2>
            <form method="post">
                <label>
                    Admin Password:
                    <input name="password" type="password" required placeholder="*********">
                </label><br>
                <label>
                    Services (one per line):
                    <textarea rows="6" name="services" required placeholder="Website
Api
Documentation"></textarea>
                </label><br>
                <button type="submit">Setup</button>
            </form>
        </div>
    </article>
    </body>
    </html>
	<?php die();
else:
	$newServices = [];
    foreach (explode("\n", $_POST['services']) as $service) {
        $newServices[urlencode($service)] = $service;
    }

//    var_dump($_POST['services']);

	$data = [
		"password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
		"states" => [
			"operational" => [
				"color" => "Green",
				"title" => "Operational"
			],
			"upcoming-maintenance" => [
				"color" => "Blue",
				"title" => "Upcoming maintenance"
			],
			"under-maintenance" => [
				"color" => "Red",
				"title" => "Under maintenance"
			],
			"experiencing-issues" => [
				"color" => "Orange",
				"title" => "Experiencing issues"
			],
			"down" => [
				"color" => "Red",
				"title" => "Malfunction"
			]
		],
        "services" => $newServices,
		"entries" => []
	];
	Data::set($data);
endif; ?>


