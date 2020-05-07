<?php
require_once 'Data.php';

if (!file_exists(__DIR__ . '/data.json')) {
	require 'setup.php';
}

require_once 'Service.php';
require_once 'Message.php';