<?php declare(strict_types = 1);

namespace LidskaSila\SmsManager;

class InvalidNumberException extends \Exception
{

	public function __construct(string $phoneNumber)
	{
		parent::__construct($phoneNumber . ' is invalid for SmsManager');
	}
}
