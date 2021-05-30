<?php

namespace App\Model;

use Nette;
use \Contributte\Translation\Translator;


class ArticlesRepository
{
    /** @var Translator */
    private $translator;

    /** @var string */
    private $defaultLocale;

	/** @var Nette\Database\Explorer */
	private $database;

	public $uploadDir = '/upload/articles/';

	public function __construct(
        Translator $translator,
        Nette\Database\Explorer $database
    )
	{
        $this->translator = $translator;
		$this->database = $database;

        $this->defaultLocale = $this->translator->getDefaultLocale();
	}

	public function findAll()
	{
		return $this->database->table('article');
	}

	public function findAllTranslations()
    {
        return $this->database->table('article_translation');
    }

    public function findArticleTranslations(int $articleId)
    {
        return $this->database->table('article_translation')->where('article_id', $articleId);
    }

    public function findAllTags()
    {
        return $this->database->table('article_tag');
    }

    public function findAllWithTranslation(string $lang = 'en')
    {
        return $this->findAllTranslations()->where('locale', $lang)->order('date_created DESC');
    }

    public function findArticleTranslation(string $lang = 'en', $id = null)
    {
        return $this->findAllTranslations()->where('locale = ? && article_id = ?', $lang, $id)->order('date_created DESC');
    }

	public function findArticleImages(int $articleId)
    {
        return $this->database->table('article_images')->where('article_id', $articleId);
    }

	public function findAllImages()
	{
		return $this->database->table('article_images');
	}

	public function saveArticleGallery(array $photos, int $articleId)
	{
		foreach ($photos as $photo) {
			$this->findAllImages()->insert([
                'article_id' => $articleId,
                'description' => $photo,
				'filename' => $photo
			]);
		}
	}
}
