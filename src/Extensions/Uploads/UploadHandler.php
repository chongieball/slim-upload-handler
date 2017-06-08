<?php 

namespace App\Extensions\Uploads;

use Sirius\Upload\Handler as SiriusHandler;

class UploadHandler extends SiriusHandler
{

	public function __construct($path)
	{
		parent::__construct($path);
	}

	public function setRules($rules, $title)
	{
		foreach ($rules as $key => $value) {
			end($value);
			$keyMessage = key($value);
			if ($keyMessage != 'message') {
				throw new \Exception('Message must be End of Array');
			}
			$message = array_pop($value);
			$this->addRule($key, $value, $message, $title);
		}

		return $this;
	}

	public function setName($name)
	{
		$this->setPrefix($name);

		return $this;
	}

	public function upload($name)
	{
		$upload = $this->process($name);

		if ($upload->isValid()) {
			$upload->confirm();
			if (count($name['name'] > 1)) {
				foreach ($upload as $key => $value) {
					$result[$key] = $value->name;
				}
			}

			if (is_string($name['name'])) {
				$result = $upload->name;
			}
		} else {
			$upload->clear();
			$message = $upload->getMessages();

			foreach ($message as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $keyValue => $valueValue) {
						$result['errors'][$key][] = (string) $valueValue;
					}
				} else {
					$result['errors'][] = (string) $value;
				}
			}
		}
		return $result;
	}
}