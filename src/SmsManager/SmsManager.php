<?php declare(strict_types = 1);

namespace LidskaSila\SmsManager;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

class SmsManager
{

	const XML_BASE_PATH = 'http://xml-api.smsmanager.cz/';
	const XML_PATH_SEND = 'Send';

	/** @var string */
	protected $username;

	/** @var string */
	protected $password;

	/** @var array */
	protected $xmlClient;

	public function __construct()
	{
		$this->xmlClient = new Client(
			[
				'base_uri' => self::XML_BASE_PATH,
			]
		);
	}

	public function setAuth(string $username, string $password): void
	{
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @param Sms $sms
	 *
	 * @return Response|bool
	 * @throws SmsException
	 * @throws SmsManagerException
	 */
	public function sendSms(Sms $sms)
	{
		$xml = $this->buildXml($sms);

		if ($xml) {
			try {
				$response = $this->xmlClient->post(
					self::XML_PATH_SEND,
					[
						'multipart' => [
							[
								'name'     => 'XMLDATA',
								'contents' => $xml,
							],
						],
					]
				);

				return $this->buildResponseData($response);
			} catch (ClientException $e) {
				$response = Parser::parseXmlResponseBody($e->getResponse());

				/** @noinspection PhpUndefinedFieldInspection */
				throw new SmsManagerException(
					implode(', ', $sms->getRecipients()) . ': ' . (string) $response->Response[0]
				);
			} catch (ServerException $e) {
				$response = Parser::parseXmlResponseBody($e->getResponse());

				/** @noinspection PhpUndefinedFieldInspection */
				throw new SmsManagerException((string) $response->Response[0]);
			}
		}

		return false;
	}

	protected function buildXml(Sms $sms): ?string
	{
		$xml           = new \SimpleXMLElement('<RequestDocument/>');
		$requestHeader = $xml->addChild('RequestHeader');
		$requestHeader->addChild('Username', $this->username);
		$requestHeader->addChild('Password', $this->password);
		$request = $xml
			->addChild('RequestList')
			->addChild('Request');

		//set SMS type
		$request
			->addAttribute('Type', $sms->getType());

		//set SMS sender if set
		if ($sms->getSender()) {
			$request
				->addAttribute('Sender', $sms->getSender());
		}

		//set message
		$request->addChild('Message', $sms->getMessage())->addAttribute('Type', 'Text');

		// set recipients
		$numberList = $request->addChild('NumbersList');

		$hasAnyNumber = false;
		foreach ($sms->getRecipients() as $recipient) {
			Validators::isE164($recipient);
			$numberList->addChild('Number', $recipient);
			$hasAnyNumber = true;
		}

		/* removes <?xml version="1.0"?> */
		$dom = dom_import_simplexml($xml);
		$xml = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);

		return $hasAnyNumber ? $xml : null;
	}

	protected function buildResponseData(ResponseInterface $response): Response
	{
		$result = new \SimpleXMLElement((string) $response->getBody());

		/** @noinspection PhpUndefinedFieldInspection */
		$responseId = (int) $result->Response['ID'];
		/** @noinspection PhpUndefinedFieldInspection */
		$responseType = (string) $result->Response['Type'];

		$response = new Response($responseId, $responseType);

		/** @var \SimpleXMLElement $responseRequestList */
		/** @noinspection PhpUndefinedFieldInspection */
		$responseRequestList = $result->ResponseRequestList;

		/** @noinspection PhpUndefinedFieldInspection */
		foreach ($responseRequestList->ResponseRequest as $request) {
			/** @noinspection PhpUndefinedFieldInspection */
			$responseRequest = new ResponseRequest(
				(int) $request->RequestID,
				(int) $request->CustomID,
				(int) $request['SmsCount'],
				(float) $request['SmsPrice']
			);

			/** @var \SimpleXMLElement $responseNumbersList */
			/** @noinspection PhpUndefinedFieldInspection */
			$responseNumbersList = $request->ResponseNumbersList;
			/** @noinspection PhpUndefinedFieldInspection */
			foreach ($responseNumbersList->Number as $phoneNumber) {
				$responseRequest->addNumber((string) $phoneNumber);
			}

			$response->addResponseRequest($responseRequest);
		}

		return $response;
	}
}
