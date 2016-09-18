<?php

namespace LidskaSila\SmsManager;

class ResponseRequest
{

	/** @var string */
	protected $requestId;

	/** @var string */
	protected $customId;

	/** @var array */
	protected $numbers = [];

	/** @var int */
	protected $smsCount;

	/** @var float */
	protected $smsPrice;

	/**
	 * @return string
	 */
	public function getRequestId()
	{
		return $this->requestId;
	}

	/**
	 * @param string $requestId
	 */
	public function setRequestId($requestId)
	{
		$this->requestId = $requestId;
	}

	/**
	 * @return string
	 */
	public function getCustomId()
	{
		return $this->customId;
	}

	/**
	 * @param string $customId
	 */
	public function setCustomId($customId)
	{
		$this->customId = $customId;
	}

	/**
	 * @param string $number
	 */
	public function addNumber(string $number)
	{
		$this->numbers[] = $number;
	}

	/**
	 * @return array
	 */
	public function getNumbers()
	{
		return $this->numbers;
	}

	/**
	 * @param array $numbers
	 */
	public function setNumbers($numbers)
	{
		$this->numbers = $numbers;
	}

	/**
	 * @return int
	 */
	public function getSmsCount()
	{
		return $this->smsCount;
	}

	/**
	 * @param int $smsCount
	 */
	public function setSmsCount($smsCount)
	{
		$this->smsCount = $smsCount;
	}

	/**
	 * @return float
	 */
	public function getSmsPrice()
	{
		return $this->smsPrice;
	}

	/**
	 * @param float $smsPrice
	 */
	public function setSmsPrice($smsPrice)
	{
		$this->smsPrice = $smsPrice;
	}

}