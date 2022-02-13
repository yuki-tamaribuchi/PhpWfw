<?php
namespace phpwfw\server\Server;

include_once('phpwfw/server/Worker.php');

use phpwfw\server\Server\Worker as ServerWorker;

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

class Server {
	private string $serverIpAddr;
	private int $serverPort;
	private $serverSocket;

	public function __construct(string $serverIpAddr, int $serverPort) {
		$this->serverIpAddr = $serverIpAddr;
		$this->serverPort = $serverPort;
	}

	public function serve() {
		if (($this->serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
			print('socket_create() failed: '.socket_strerror(socket_last_error()).'\n');
		}

		if (socket_bind($this->serverSocket, $this->serverIpAddr, $this->serverPort) === false) {
			print('socket_bin() failed: '.socket_strerror(socket_last_error($this->serverSocket)));
		}

		if (socket_set_option($this->serverSocket, SOL_SOCKET, SO_REUSEADDR, 1) === false) {
			print('socket_set_option() failed: '.socket_strerror(\socket_last_error($this->serverSocket)));
		}

		if (socket_listen($this->serverSocket, 5) === false) {
			print('socket_listen() failed: '.socket_strerror(socket_last_error($this->serverSocket)));
		}

		try {
			do {
				if(($clientSocket = socket_accept($this->serverSocket)) === false) {
					print('socket_accept() failed: '.socket_strerror(socket_last_error($this->serverSocket)));
					break;
				}
				$worker = new ServerWorker($clientSocket);
				$worker->start();
			} while (true);
		} finally {
			socket_close($this->serverSocket);
		}
		
	}
}


?>