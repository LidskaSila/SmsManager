<?php declare(strict_types = 1);

namespace LidskaSila\SmsManager;

class Sms
{

	const REQUEST_TYPE_LOW     = 'lowcost';
	const REQUEST_TYPE_ECONOMY = 'economy';
	const REQUEST_TYPE_HIGH    = 'high';
	const REQUEST_TYPE_DEFAULT = self::REQUEST_TYPE_HIGH;

	/** @var string */
	protected $message;

	/** @var string */
	protected $type;

	/** @var array */
	protected $recipients = [];

	/** @var string */
	protected $sender;

	public function __construct(string $message, ?string $type, string $sender, array $recipients)
	{
		$this->message = $message;

		if ($type === null) {
			$type = self::REQUEST_TYPE_DEFAULT;
		}
		$this->type       = $type;
		$this->sender     = $sender;
		$this->recipients = $recipients;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getRecipients(): array
	{
		return $this->recipients;
	}

	public function getSender(): string
	{
		return $this->sender;
	}

	public function setRecipients(array $recipients): void
	{
		$this->recipients = $recipients;
	}
}
