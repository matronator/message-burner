<?php

namespace App\Model;

use App\Services\HashService;
use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\DateTime;

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

	public function getMessage(string $hash): Selection|null
	{
		$id = HashService::hashToId($hash);
		if ($id === -1) {
			return null;
		}
		return $this->findAll()->get($id);
	}

	public function getImage(string $hash): Selection|null
	{
		$id = HashService::hashToId($hash, 'images');
		if ($id === -1) {
			return null;
		}
		return $this->findAllImages()->get($id);
	}

	public function messageRead(string $hash)
	{
		$this->getMessage($hash)->delete();
	}

	public function deleteExpiredMessages()
	{
		$messages = $this->findAll()->where('expires_at <= ?', new DateTime('now'));
		if ($messages) {
			return $messages->delete();
		}
		return 0;
	}

	public function findAllImages(): Selection
	{
		return $this->database->table('images');
	}
}
