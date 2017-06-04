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
		$result = $this->process($name);

		if ($result->isValid()) {
			$result->confirm();
			return $result->name;
		} else {
			$result->clear();
			$message = $result->getMessages();

			foreach ($message as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $keyValue => $valueValue) {
						$error[$key][] = (string) $valueValue;
					}
				} else {
					$error[] = (string) $value;
				}
			}
			return $error;
		}
	}
}