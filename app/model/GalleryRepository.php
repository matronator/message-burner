<?php

namespace App\Model;

use Nette;


class GalleryRepository
{
	/** @var Nette\Database\Explorer */
	private $database;

	public $uploadDir = '/upload/gallery/';

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function findAll()
	{
		return $this->database->table('gallery');
	}

    public function findGalleryImages(int $galleryId)
    {
        return $this->database->table('gallery_images')->where('gallery_id', $galleryId);
    }

	public function findAllImages()
	{
		return $this->database->table('gallery_images');
	}

	public function saveGallery(array $photos, int $id)
	{
		foreach ($photos as $photo) {
			$this->findAllImages()->insert([
				'gallery_id' => $id,
				'description' => $photo,
				'filename' => $photo
			]);
		}
	}
}
