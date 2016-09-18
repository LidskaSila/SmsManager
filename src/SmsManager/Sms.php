<?php

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
	protected $type = self::REQUEST_TYPE_DEFAULT;

	/** @var array */
	protected $recipients = [];

	/** @var string|null */
	protected $sender;

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function getRecipients()
	{
		return $this->recipients;
	}

	/**
	 * @param array $recipients
	 */
	public function setRecipients($recipients)
	{
		$this->recipients = $recipients;
	}

	/**
	 * @return null|string
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * @param null|string $sender
	 */
	public function setSender($sender)
	{
		$this->sender = $sender;
	}

}