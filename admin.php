<?php

use ServerStatusMonitor\Data;

require 'app/app.php';

$data = Data::get();

session_start();

$hash = Data::get()->password;
$message = '';

if (isset($_POST['action']) && $_POST['action'] === 'login') {
	if (
		isset($_POST['password'])
		&& password_verify($_POST['password'], $hash)
	) {
		$_SESSION['authenticated'] = $_SERVER['REMOTE_ADDR'];
	} else {
		$message = 'Wrong password';
	}
} else if (isset($_POST['action']) && $_POST['action'] === 'logout') {
	unset($_SESSION['authenticated']);
} else if (isset($_POST['action']) && $_POST['action'] === 'save') {
	$entry = [
		"services" => $_POST['services'],
        "state" => $_POST['state'],
        "message" => $_POST['message'],
        "date" => date(DATE_ATOM)
	];

	array_push($data->entries, (object) $entry);
	Data::set($data);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex,nofollow">
    <title>Server Status Admin</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<h1>Admin</h1>
<?
if (!isset($_SESSION['authenticated']) ||
	$_SESSION['authenticated'] !== $_SERVER['REMOTE_ADDR']) {
	// Logged out
	?>
    <article>
        <form method="post">
			<?= "<p>$message</p>" ?>
            <label>
                Password:
                <input placeholder="Password" type="password" name="password" required>
            </label>
            <button type="submit" value="login" name="action">Log in</button>
        </form>
    </article>
	<?
	die();
} else {
	// Logged In
	?>
    <article>
        <div>
            <h2>Welcome to the backend</h2><br>

            <form method="post">
                <button type="submit" name="action" value="logout">Log out</button>
            </form>
            <br>
            <h2>Submit new status</h2><br>
			<? echo "<p>$message</p>" ?>
            <form method="post">
                <label>
                    New state:<br>
                    <select required name="state">
						<? foreach (array_keys((array)$data->states) as $stateKey): ?>
                            <option value="<?= $stateKey ?>">
								<?= $data->states->$stateKey->title ?>
                            </option>
						<? endforeach; ?>
                    </select>
                </label><br>
                <label>
                    Affected Services:<br>
                    <select required name="services[]" multiple>
						<? foreach (array_keys((array)$data->services) as $service): ?>
                            <option value="<?= $service ?>"><?= $data->services->$service ?></option>
						<? endforeach; ?>
                    </select>
                </label><br>
                <label>
                    Message:<br>
                    <textarea required name="message"></textarea>
                </label><br>
                <button type="submit" name="action" value="save">Save</button>
            </form>
        </div>
    </article>
	<?
}
?>
</body>
</html>


