<?php

namespace App\Model;

use App\Libs\HashService;
use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class MessagesRepository
{
	/** @var Nette\Database\Explorer */
	private $database;

	public $uploadDir = '/upload/messages/';

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function findAll(): Selection
	{
		return $this->database->table('messages');
	}

	public function getMessage(string $hash)
	{
		$id = HashService::hashToId($hash);
		if ($id === -1) {
			return null;
		}
		return $this->findAll()->get($id);
	}

	public function messageRead(string $hash)
	{
		$this->getMessage($hash)->delete();
	}
}
