<?php
namespace phpwfw\server\Server;

require_once('phpwfw/http/request/Request.php');
use phpwfw\http\request\Request\Request as Request;

require_once('phpwfw/http/request/IRequest.php');
use phpwfw\http\request\Request\IRequest;


class Worker {
	private $clientSocket;
	private IRequest $request;

	public function __construct($clientSocket) {
		$this->clientSocket = $clientSocket;
	}

	public function start() {
		$this->request = new Request($this->clientSocket);
		
		socket_close($this->clientSocket);
	}
}
?>