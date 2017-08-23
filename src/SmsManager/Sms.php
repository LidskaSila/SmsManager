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

	/** @var string|null */
	protected $sender;

	/** @var int|null */
	protected $customID;

	public function __construct(string $message, ?string $type, array $recipients, ?string $sender = null, ?int $customID = null)
	{
		$this->message = $message;

		if ($type === null) {
			$type = self::REQUEST_TYPE_DEFAULT;
		}
		$this->type   = $type;
		$this->sender = $sender;
		$this->customID = $customID;

		foreach ($recipients as $recipient) {

			if (!
			(
				preg_match('/^\+420(?:(?:60[1-8]|7(?:0[2-5]|[2379]\d))\d{6})$/', $recipient) ||
				(
					preg_match('/^\+4219(?:0(?:[1-8]\d|9[1-9])|(?:1[0-24-9]|4[04589]|50)\d)\d{5}$/', $recipient) &&
					$type !== self::REQUEST_TYPE_LOW
				)
			)
			) {
				throw new InvalidNumberException($recipient);
			}
		}

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

	public function getSender(): ?string
	{
		return $this->sender;
	}

	public function getCustomID(): ?int
	{
		return $this->customID;
	}
}
