<?php
namespace phpwfw\PhpWfw;

require_once('server/Server.php');
use phpwfw\server\Server\Server as Server;

class PhpWfw {
	private Server $server;

	public function __construct()
	{
		$this->server = new Server("127.0.0.1", 8000);
		$this->server->serve();
	}
}