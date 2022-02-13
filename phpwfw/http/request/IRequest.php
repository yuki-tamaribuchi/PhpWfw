<?php
namespace phpwfw\http\request\Request;

interface IRequest {
	public function getRequestLine();
	public function getRequestOptions();
	public function getRequestBody();
}
?>