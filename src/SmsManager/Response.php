<?php

namespace LidskaSila\SmsManager;

class Response
{

	const STATUS_OK    = 'OK';
	const STATUS_ERROR = 'ERROR';

	/** @var int */
	protected $id;

	/** @var string */
	protected $type;

	/** @var string */
	protected $isOk;

	/** @var ResponseRequest[] */
	protected $responseRequests = [];

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
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
		$this->isOk = $type === self::STATUS_OK;

		$this->type = $type;
	}

	/**
	 * @param ResponseRequest $responseRequest
	 */
	public function addResponseRequest(ResponseRequest $responseRequest)
	{
		$this->responseRequests[] = $responseRequest;
	}

	/**
	 * @return ResponseRequest[]
	 */
	public function getResponseRequests()
	{
		return $this->responseRequests;
	}

	/**
	 * @param ResponseRequest[] $responseRequests
	 */
	public function setResponseRequests($responseRequests)
	{
		$this->responseRequests = $responseRequests;
	}

}