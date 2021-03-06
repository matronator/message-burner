<?php

use Nette\Http\FileUpload;
use Nette\Utils\Image,
	Nette\Utils\Strings;

class ImageStorage
{
	private $dir;

	public function __construct($dir)
	{
		$this->dir = $dir;
	}

	public function saveImage(FileUpload $file, string $subdir, ?string $gallery = null)
	{
		$fileName = $this->getRandomName($file->name);
		$sub = $gallery ? $subdir . '/' . $gallery . '/' : $subdir;
		$imgUrl = $this->dir . $sub . $fileName;
		$file->move($imgUrl);
		return (object)[
			'filename' => $fileName,
			'path' => $imgUrl,
			'encryptPath' => $this->dir . $sub . 'encrypted/' . $fileName,
		];
	}

	public function saveFile($file, string $subdir, ?string $gallery = null)
	{
		$sub = $gallery ? $subdir . '/' . $gallery . '/' : $subdir;
		$ext = explode('.', $file->name);
		$ext = '.'.$ext[count($ext)-1];
		$fileNameNoExtension = preg_replace("/\.[^.]+$/", "", $file->name);

		$fileName = Strings::webalize($fileNameNoExtension).$ext;

		$fileUrl = $this->dir . $sub . $fileName;
		$file->move($fileUrl);
		return (object)[
			'filename' => $fileName,
			'path' => $fileUrl,
			'encryptPath' => $this->dir . $sub . 'encrypted/' . $fileName,
		];
	}

	private function getRandomName(string $fileName)
	{
		$ext = explode('.', $fileName);
		$ext = '.'.$ext[count($ext)-1];
		return md5(time().rand()) . $ext;
	}

	// delete image
	public function delete(string $fileName, string $subdir, ?string $gallery = null)
	{
		$subdir = $gallery ? $subdir . '/' . $gallery . '/' : $subdir;
		unlink($this->dir . $subdir . $fileName);
	}

	public function saveGallery(array $photos, string $subdir, ?string $gallery = null)
	{
		$savedPhotos = [];
		$subdir = $gallery ? $subdir . '/' . $gallery . '/' : $subdir;

		foreach($photos as $photo){
			$fileName = $this->getRandomName($photo->name);
			$imgUrl = $this->dir . $subdir . $fileName;
			$photo->move($imgUrl);

			$savedPhotos[] = $fileName;
		}

		return $savedPhotos;
	}

}
