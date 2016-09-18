<?php

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
		$this->xmlClient = new Client([
			'base_uri' => self::XML_BASE_PATH,
		]);
	}

	/**
	 * @param string $username
	 * @param string $password
	 */
	public function setAuth(string $username, string $password)
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
				$response = $this->xmlClient->post(self::XML_PATH_SEND, [
					'multipart' => [
						[
							'name'     => 'XMLDATA',
							'contents' => $xml,
						],
					],
				]);

				return $this->buildResponseData($response);
			} catch (ClientException $e) {
				$response = Parser::parseXmlResponseBody($e->getResponse());

				throw new SmsManagerException((string) $response->Response[0]);
			} catch (ServerException $e) {
				$response = Parser::parseXmlResponseBody($e->getResponse());

				throw new SmsManagerException((string) $response->Response[0]);
			}
		}

		return false;
	}

	/**
	 * @param Sms $sms
	 *
	 * @return null|string
	 *
	 * @throws SmsException
	 */
	protected function buildXml(Sms $sms)
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

	/**
	 * @param ResponseInterface $response
	 *
	 * @return Response
	 */
	protected function buildResponseData(ResponseInterface $response)
	{
		$result = new \SimpleXMLElement((string) $response->getBody());

		$responseId   = (int) $result->Response['ID'];
		$responseType = (string) $result->Response['Type'];

		$response = new Response();
		$response->setId($responseId);
		$response->setType($responseType);

		/** @var \SimpleXMLElement $responseRequestList */
		$responseRequestList = $result->ResponseRequestList;

		foreach ($responseRequestList->ResponseRequest as $request) {
			$responseRequest = new ResponseRequest();
			$responseRequest->setRequestId((int) $request->RequestID);

			$responseRequest->setSmsCount((int) $request['SmsCount']);
			$responseRequest->setSmsPrice((float) $request['SmsPrice']);

			$responseRequest->setCustomId((int) $request->CustomID);

			/** @var \SimpleXMLElement $responseNumbersList */
			$responseNumbersList = $request->ResponseNumbersList;
			foreach ($responseNumbersList->Number as $phoneNumber) {
				$responseRequest->addNumber((string) $phoneNumber);
			}

			$response->addResponseRequest($responseRequest);
		}

		return $response;
	}

}