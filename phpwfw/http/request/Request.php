<?php
namespace phpwfw\http\request\Request;


require_once('phpwfw/exception/http/request/LoadRequestFailedException.php');
use phpwfw\exception\http\request\LoadRequestFailedException;

require_once('phpwfw/http/request/IRequest.php');
use phpwfw\http\request\Request\IRequest;

class Request implements IRequest {
	private $clientSocket;
	private $reqeustLine;
	private $requestOptions;
	private $requestBody;

	public function __construct($clientSocket)
	{
		$this->clientSocket = $clientSocket;
		if($this->load_request() === false) {
			throw new LoadRequestFailedException();
		}
	}

	private function load_request() {
		$requestData = '';
		socket_recv($this->clientSocket, $requestData, 4096, 0);


		$splitedRequestHeaderAndBody = explode("\r\n\r\n", $requestData, 2);

		$requestHeader = $splitedRequestHeaderAndBody[0];
		$this->requestBody = $splitedRequestHeaderAndBody[1];
		
		$splitedRequestHeader = explode("\r\n", $requestHeader);

		$splitedRequestLine = explode(" ", $splitedRequestHeader[0]);
		$this->reqeustLine['method'] = $splitedRequestLine[0];
		$this->reqeustLine['uri'] = $splitedRequestLine[1];
		$this->reqeustLine['protocol'] = $splitedRequestLine[2];

		$requestOptions = array_splice($splitedRequestHeader, 1, count($splitedRequestHeader)-2);
		foreach($requestOptions as $option) {
			$splitedOption = explode(": ", $option);
			$this->requestOptions[$splitedOption[0]] = $splitedOption[1];
		}
		return true;
	}

	public function getRequestLine() {
		return $this->reqeustLine;
	}

	public function getRequestOptions() {
		return $this->requestOptions;
	}

	public function getRequestBody() {
		return $this->requestBody;
	}
}

?>