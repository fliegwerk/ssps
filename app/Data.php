<?php


namespace ServerStatusMonitor;

class Data
{
	static function get()
	{
		return json_decode(file_get_contents(__DIR__ . '/data.json'));
	}

	static function set($data)
	{
		if (touch(__DIR__ . '/data.json')) {
			if (!file_put_contents(__DIR__ . '/data.json', json_encode($data))) {
				die('Could not write to data.json file. Please check your permissions.');
			}
		} else {
			die('Could not write to data.json file. Please check your permissions.');
		}
	}
}