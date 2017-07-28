<?php declare(strict_types = 1);

namespace LidskaSila\SmsManager;

class Validators
{

	/**
	 * https://en.wikipedia.org/wiki/E.164
	 *
	 * @param string $phoneNumber
	 *
	 * @throws \LidskaSila\SmsManager\SmsException
	 */
	public static function isE164(string $phoneNumber): void
	{
		if (!preg_match('/\A\+(\d{1,3})([\d]{1,14})\z/', $phoneNumber)) {
			throw new SmsException('Number is not valid according to E.164 recommendation');
		}
	}
}
