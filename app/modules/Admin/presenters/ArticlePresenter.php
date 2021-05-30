<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model,
    Nette,
    Nette\Utils\Strings,
    Nette\Application\UI\Form,
    Nette\Utils\Image,
    Nette\Utils\DateTime;
use Tracy\Debugger;

/////////////////////// ADMIN: ARTICLES PRESENTER ///////////////////////

final class ArticlePresenter extends BasePresenter
{
	/** @var Model\ArticlesRepository */
	private $articleRepository;

	/** @var \ImageStorage */
	public $imageStorage;

	public function __construct(
		Model\ArticlesRepository $articleRepository,
		\ImageStorage $storage
	){
		$this->articleRepository = $articleRepository;
		$this->imageStorage = $storage;
	}

    protected function startup()
    {
        parent::startup();
    }

	/*********************** RENDER VIEWS ***********************/
	public function renderDefault()
	{
        $this->template->data = $this->articleRepository->findAllWithTranslation($this->defaultLocale);
	}


	public function renderEdit(int $id = null)
    {
        $form = $this['articleForm'];
        if ($id) {
            $translation = $this->articleRepository->findArticleTranslation($this->defaultLocale, $id)->fetch();
            $articles = $this->articleRepository->findArticleTranslation($this->defaultLocale, $id)->get($translation->id);

            $data = array_map(function ($item) {
                return $item;
            }, $articles->toArray());
            $data['date'] = $articles->article->date;
            $data['visible'] = $articles->article->visible;
            $data['image_top'] = $articles->article->image_top;
            $data['image_bottom'] = $articles->article->image_bottom;

            $this->template->articleDir = $data['htaccess'];
            if (isset($data['image_top']) && $data['image_top'] != null) {
				$this->template->mainImageName = $data['image_top'];
			}
            if (isset($data['image_bottom']) && $data['image_bottom'] != null) {
				$this->template->bottomImageName = $data['image_bottom'];
			}
            $this->template->articleMedia = $this->articleRepository->findArticleImages($id)->fetchAll();
            $this->template->id = $id;
        } else {
            $data = [];
            $data['date'] = new DateTime();
            $data['visible'] = 1;

            $this->template->articleMedia = [];
        }
        $form->setDefaults($data);
    }

    /*********************** ACTIONS ***********************/

	public function actionDelete(int $id)
    {
        $row = $this->articleRepository->findAll()->get($id);
        $translation = $this->articleRepository->findArticleTranslation($this->defaultLocale, $id)->fetch();
        // $photos = $this->articleRepository->findAllImages()->where('article_id', $id);

        if (!$row) {
            $this->flashMessage('Záznam nenalezen!');
        } else {

			if ($row->image_top) {
				$this->imageStorage->delete($row->image_top, $this->articleRepository->uploadDir, $translation->htaccess);
			}
			if ($row->image_bottom) {
				$this->imageStorage->delete($row->image_bottom, $this->articleRepository->uploadDir, $translation->htaccess);
			}

            $photos = $this->articleRepository->findArticleImages($id)->fetchAll();
			foreach ($photos as $photo) {
                $this->imageStorage->delete($photo->filename, $this->articleRepository->uploadDir, $translation->htaccess);
            }
            $this->articleRepository->findArticleImages($id)->delete();

            $this->articleRepository->findArticleTranslation($this->defaultLocale, $id)->delete();
            // $this->articleRepository->findAllTags()->where('article_id', $id)->delete();
            $this->articleRepository->findAll()->where('id', $id)->delete();

            $this->flashMessage('Záznam úspěšně smazán!');
        }

        $this->redirect('default');
    }

	public function actionDeleteImage(int $id)
	{
		$row = $this->articleRepository->findAllImages()->get($id);

		if (!$row) {
			$this->flashMessage('Záznam nenalezen!');
			$this->redirect('default');
		} else {
            $article = $row->ref('article', 'article_id');
            $translation = $this->articleRepository->findArticleTranslation($this->defaultLocale, $article->id)->fetch();
			$this->articleRepository->findAllImages()->wherePrimary($id)->delete();
			$this->imageStorage->delete($row->filename, $this->articleRepository->uploadDir, $translation->htaccess);
			$this->flashMessage('Obrázek úspěšně smazán!');
			$this->redirect('edit', $article->id);
		}
	}

	public function actionShow(int $id, bool $visible)
    {
        $this->articleRepository->findAll()->where('id', $id)->update(['visible' => $visible]);
    }

	/*********************** COMPONENT FACTORIES ***********************/
	/**
	* Edit form factory.
	* @return Form
	*/
	protected function createComponentArticleForm()
    {
        $form = new Form;

        $form->addText('title', 'Název *')
            ->setHtmlAttribute('class', 'uk-input')
            ->setRequired('Článek musí mít název.');

        $form->addText('htaccess', 'URL')
            ->setHtmlAttribute('class', 'uk-input');

        $form->addCheckbox('visible', ' Zobrazit článek?')
			->setHtmlAttribute('class', 'uk-checkbox');

        $form->addTextArea('perex', 'Perex')
            ->setHtmlAttribute('class', 'js-wysiwyg');

        $form->addTextarea('text', 'Text *')
            ->setHtmlAttribute('class', 'js-wysiwyg')
            ->setRequired('Článek musí obsahovat text.');

        // $form->addText('tags', 'Štítky');

        $form->addText('date', 'Datum')
            ->setHtmlAttribute('class', 'js-date uk-input');

        $form->addUpload('image_top', 'Úvodní obrázek')
            ->addRule($form::IMAGE, 'Soubor musí být obrázek typu JPEG, PNG, GIF, nebo WebP.');

        $form->addUpload('image_bottom', 'Obrázek pod článkem')
            ->addRule($form::IMAGE, 'Soubor musí být obrázek typu JPEG, PNG, GIF, nebo WebP.');

        $form->addHidden('clear_top_image', 'false');
        $form->addHidden('clear_bottom_image', 'false');

        $form->addMultiUpload('files', 'Galerie')
			->addRule($form::IMAGE, 'Soubory musí být obrázky typu JPEG, PNG, GIF, nebo WebP.');

        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'articleFormSucceeded'];
        return $form;
    }


	public function articleFormSucceeded(Form $form, $values)
    {
        $id = (int) $this->getParameter('id');

        // Insert primary record
        $primaryData = [];

        // $primaryData['title'] = $values->title;
        // $primaryData['htaccess'] = $values->htaccess ? $values->htaccess : Strings::webalize($values->title);
        $htaccess = Strings::webalize($values->title);

        // if( !isset($title) ) {
        //     $this->flashMessage('Nevložili jste titulek článku. Data nemohla být uložena.');
        //     $this->redirect('default');
        // }

        // Set htaccess & date
        $primaryData['date'] = $values->date ? $values->date : new DateTime();
        $primaryData['user_id'] = $this->user->getIdentity()->id;

        // Upload image
        if (isset($values->image_top) && $values->clear_top_image !== 'true') {
            if ($values->image_top->isOk()) { // There is no error, the file uploaded with success
                $imageTopInsert = $this->imageStorage->saveImg($values->image_top, $this->articleRepository->uploadDir, $htaccess);
            }
        }
        if (isset($values->image_bottom) && $values->clear_bottom_image !== 'true') {
            if ($values->image_bottom->isOk()) { // There is no error, the file uploaded with success
                $imageBottomInsert = $this->imageStorage->saveImg($values->image_bottom, $this->articleRepository->uploadDir, $htaccess);
            }
        }

        $savedPhotos = $this->imageStorage->saveGallery($values->files, $this->articleRepository->uploadDir, $htaccess);
		unset($values->files);

        // Insert / update primary data
        if ($id > 0) {
            $row = $this->articleRepository->findAll()->get($id);

            $primaryData['date_updated'] = new DateTime();
            $primaryData['date_created'] = $row->date_created;
            if (isset($values->clear_top_image) && $values->clear_top_image === 'true') {
                $primaryData['image_top'] = NULL;
                $this->imageStorage->delete($row->image_top, $this->articleRepository->uploadDir, $htaccess);
            } else {
                $primaryData['image_top'] = isset($imageTopInsert) ? $imageTopInsert : $row->image_top;
            }
            if (isset($values->clear_bottom_image) && $values->clear_bottom_image === 'true') {
                $primaryData['image_bottom'] = NULL;
                $this->imageStorage->delete($row->image_bottom, $this->articleRepository->uploadDir, $htaccess);
            } else {
                $primaryData['image_bottom'] = isset($imageBottomInsert) ? $imageBottomInsert : $row->image_bottom;
            }

            $row->update($primaryData);

            $this->articleRepository->findArticleTranslations($id)->delete();
            $this->articleRepository->saveArticleGallery($savedPhotos, $id);
            // $this->articleRepository->findAllTags()->where('article_id', $id)->delete();
            $this->flashMessage('Záznam byl úspěšně upraven.');
            $articleId = $id;
        } else {
            $primaryData['date_created'] = new DateTime();
            $primaryData['image_top'] = isset($imageTopInsert) ? $imageTopInsert : NULL;
            $primaryData['image_bottom'] = isset($imageBottomInsert) ? $imageBottomInsert : NULL;

            $row = $this->articleRepository->findAll()->insert($primaryData);
            $articleId = $row->id;

            $this->articleRepository->saveArticleGallery($savedPhotos, $articleId);

            $this->flashMessage('Záznam byl úspěšně přidán.');
        }

        // Insert translations
        foreach ( array($this->defaultLocale) as $lang ) {
            $this->articleRepository->findAllTranslations()->insert([
                'article_id' => $articleId,
                'locale' => $lang,
                'title' => $values->title,
                'perex' => $values->perex,
                'text' => $values->text,
                'htaccess' => $htaccess,
                'date_created' => $primaryData['date_created'],
                'date_updated' => new DateTime()
            ]);

            // Insert tags
            // $tags = explode(',', $values->{'tags_'.$lang});
            // foreach ($tags as $tag) {
            //     if ($tag)
            //         $this->articleRepository->findAllTags()->insert([
            //             'article_id' => $articleId,
            //             'locale' => $lang,
            //             'title' => trim($tag),
            //             'htaccess' => Strings::webalize($tag),
            //             'date_updated' => new DateTime()
            //         ]);
            // }
        }


        // Redirect
        $this->redirect('default');

    }
}
