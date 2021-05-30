<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use ImageStorage;

/////////////////////// ADMIN: GALLERY PRESENTER ///////////////////////

final class GalleryPresenter extends BasePresenter
{
	/** @var Model\GalleryRepository */
	private $galleryRepository;

	/** @var ImageStorage */
	private $imageStorage;

	public function __construct(
		Model\GalleryRepository $galleryRepository,
		ImageStorage $imageStorage
	){
		$this->galleryRepository = $galleryRepository;
		$this->imageStorage = $imageStorage;
	}

	protected function startup()
	{
		parent::startup();
	}

	/*********************** RENDER VIEWS ***********************/

	public function renderDefault()
	{
		$this->template->data = $this->galleryRepository->findAll()->order('date_created DESC');
	}

	public function renderEdit(int $id = null)
	{
		$form = $this['galleryForm'];
		$this->template->id = $id;
		if ($id) {
			$gallery = $this->galleryRepository->findAll()->get($id);
			if (!$gallery) {
				$this->flashMessage('Galerie nenalezena', 'danger');
				$this->redirect('default');
			}
			$defaults = array_map(function ($item) {
				return $item;
			}, $gallery->toArray());

			$this->template->galleryDir = $defaults['htaccess'];
			if (isset($defaults['image'])) {
				$this->template->mainImageName = $defaults['image'];
			}
			$this->template->galleryMedia = $this->galleryRepository->findGalleryImages($id)->fetchAll();
			$form->setDefaults($defaults);
		} else {
			$this->template->galleryMedia = [];
			$defaults = [];
			$defaults['date'] = new DateTime();
			$defaults['visible'] = 1;
			$form->setDefaults($defaults);
		}
	}

	// =========== ACTIONS ===========
	public function actionDelete(int $id)
	{
		$row = $this->galleryRepository->findAll()->get($id);
		if (!$row) {
			$this->flashMessage('Záznam nenalezen!', 'warning');
		} else {
			if ($row->image) {
				$this->imageStorage->delete($row->image, $this->galleryRepository->uploadDir, $row->htaccess);
			}
			$photos = $this->galleryRepository->findGalleryImages($id)->fetchAll();
			foreach ($photos as $photo) {
                $this->imageStorage->delete($photo->filename, $this->galleryRepository->uploadDir, $row->htaccess);
			}
			$this->galleryRepository->findAll()->where('id', $id)->delete();
			$this->flashMessage('Galerie smazána!', 'success');
		}
		$this->redirect('default');
	}

	public function actionDeleteImage(int $id)
	{
		$row = $this->galleryRepository->findAllImages()->get($id);

		if (!$row) {
			$this->flashMessage('Záznam nenalezen!');
			$this->redirect('default');
		} else {
			$gallery = $row->ref('gallery', 'gallery_id');
			$this->flashMessage('Obrázek úspěšně smazán!');
			$this->galleryRepository->findAllImages()->wherePrimary($id)->delete();
			$this->imageStorage->delete($row->filename, $this->galleryRepository->uploadDir, $gallery->htaccess);
			$this->redirect('edit', $gallery->id);
		}
	}

	// =========== FORMS ===========

	/**
	* Edit form factory.
	* @return Form
	*/
	protected function createComponentGalleryForm()
    {
		$form = new Form;

		$form->addText('title', 'Název *')
			->setRequired()
			->setHtmlAttribute('class', 'uk-input');

		$form->addCheckbox('visible', ' Zobrazit galerii?')
			->setHtmlAttribute('class', 'uk-checkbox');

		$form->addText('date', 'Datum')
			->setHtmlAttribute('class', 'js-date uk-input');

		$form->addUpload('image', 'Hlavní Fotografie')
			->addRule($form::IMAGE, 'Soubor musí být obrázek typu JPEG, PNG, GIF, nebo WebP.');

		$form->addHidden('clear_image', 'false');

		$form->addMultiUpload('files', 'Galerie')
			->addRule($form::IMAGE, 'Soubory musí být obrázky typu JPEG, PNG, GIF, nebo WebP.');

		$form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'galleryFormSucceeded'];
        return $form;
	}

	public function galleryFormSucceeded(Form $form, $values)
    {
		$id = (int) $this->getParameter('id');

        // Insert primary record
		$primaryData = [];
		$htaccess = Strings::webalize($values->title);

		$primaryData['title'] = $values->title;
		$primaryData['htaccess'] = $htaccess;
		$primaryData['visible'] = $values->visible;
		$primaryData['date'] = $values->date ? $values->date : new DateTime();
		$primaryData['user_id'] = ((object) $this->user->getIdentity()->data)->id;
		$primaryData['date_updated'] = new DateTime();

		$clearImage = false;

		if (isset($values->image) && $values->image->isOk()) { // There is no error, the file uploaded with success
			$imageToInsert = $this->imageStorage->saveImg($values->image, $this->galleryRepository->uploadDir, $htaccess);
		} else {
			if ($values->clear_image === 'true') {
				$clearImage = true;
			}
		}
		$savedPhotos = $this->imageStorage->saveGallery($values->files, $this->galleryRepository->uploadDir, $htaccess);
		unset($values->files);

		if ($id) {
			$row = $this->galleryRepository->findAll()->get($id);
			if (!$clearImage) {
				$primaryData['image'] = isset($imageToInsert) ? $imageToInsert : $row->image;
			} else {
				$primaryData['image'] = NULL;
				$this->imageStorage->delete($row->image, $this->galleryRepository->uploadDir, $htaccess);
			}

			$row->update($primaryData);
			$this->galleryRepository->saveGallery($savedPhotos, $id);
			$this->flashMessage('Galerie uložena!', 'success');
		} else {
			$primaryData['date_created'] = new DateTime();
			if (!$clearImage) {
				$primaryData['image'] = isset($imageToInsert) ? $imageToInsert : NULL;
			} else {
				$primaryData['image'] = NULL;
			}

			$savedGallery = $this->galleryRepository->findAll()->insert($primaryData);
			$this->galleryRepository->saveGallery($savedPhotos, $savedGallery->id);
			$this->flashMessage('Galerie vytvořena!', 'success');
		}

        $this->redirect('default');
	}

}
