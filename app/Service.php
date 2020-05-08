<?php


namespace ServerStatusMonitor;


use DateTime;

class Service
{
	private $name;
	public $title;
	private $data;

	private $entries;

	/**
	 * Service constructor.
	 * @param $name string
	 * @param $data mixed
	 */
	public function __construct($name, $data)
	{
		$this->name = $name;
		$this->data = $data;
		$this->title = $name;
		$this->title = $data->services->$name;

		$this->entries = array_filter($data->entries, function ($entry) use ($name) {
			$valid = true;
			$valid &= in_array($name, $entry->services);
			$valid &= new DateTime($entry->date) < new DateTime();
			return $valid;
		});

		usort($this->entries, function($a, $b) {
			return strtotime($b->date) - strtotime($a->date);
		});
	}

	public function getState()
	{
		$entries = $this->getEntries();
		if (sizeof($entries) < 1) {
			return (object)[
				"color" => "",
				"title" => "Unknown"
			];
		} else {
			$statusKey = $entries[0]->state;
			return $this->data->states->$statusKey;
		}
	}

	/**
	 * @return array
	 */
	public function getMessages(): array
	{
		$res = [];

		if ($this->getState()->color !== 'Green') {
			// Return current situation report
			$nonGreen = true;
			$i = 0;

			while ($i < sizeof($this->getEntries()) && $nonGreen) {
				array_push($res, $this->getEntries()[$i]);
				$i ++;
				if ($i >= sizeof($this->getEntries()))
					break;
				$statusKey = $this->getEntries()[$i]->state;
				$nonGreen = $this->data->states->$statusKey->color !== 'Green';
			}
		} else {
			// Entries of the last 24 hours
			return array_filter($this->getEntries(), function ($entry) {
				return strtotime('-24 hours') < strtotime($entry->date);
			});
		}

		return $res;
	}

	/**
	 * @return array
	 */
	public function getEntries(): array
	{
		return $this->entries;
	}

	/**
	 * @return Service[]
	 */
	static function get(): array
	{
		$data = Data::get();

		$res = [];

		foreach (array_keys((array)($data->services)) as $name) {
			array_push($res, new Service($name, $data));
		}

		return $res;
	}
}