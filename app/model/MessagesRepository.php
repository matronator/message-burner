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

	public function getMessage(string $hash): ?ActiveRow
	{
		$id = HashService::hashToId($hash);
		if ($id === -1) {
			return null;
		}
		return $this->findAll()->get($id);
	}

	public function getImage(string $hash): ?ActiveRow
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

	public function imageRead(string $hash)
	{
		$this->getImage($hash)->delete();
	}

	public function deleteExpiredMessages()
	{
		$messages = $this->findAll()->where('expires_at <= ?', new DateTime('now'));
		$images = $this->findAllImages()->where('expires_at <= ?', new DateTime('now'));
		$deleted = 0;
		if ($messages) {
			$deleted = $messages->delete();
		}
		if ($images) {
			foreach ($images as $image) {
				$encryptedPath = __DIR__ . '/../../../../www/upload/messages/encrypted/' . $image->filename;
				unlink($encryptedPath);
			}
			$deleted += $images->delete();
		}
		return $deleted;
	}

	public function findAllImages(): Selection
	{
		return $this->database->table('images');
	}
}
