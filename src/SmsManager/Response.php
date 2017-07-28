<?php declare(strict_types = 1);

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

	public function __construct($id, $type)
	{
		$this->id = $id;

		$this->isOk = $type === self::STATUS_OK;

		$this->type = $type;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param ResponseRequest $responseRequest
	 */
	public function addResponseRequest(ResponseRequest $responseRequest): void
	{
		$this->responseRequests[] = $responseRequest;
	}

	/**
	 * @return ResponseRequest[]
	 */
	public function getResponseRequests(): array
	{
		return $this->responseRequests;
	}
}
