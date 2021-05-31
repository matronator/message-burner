<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model;
use Nette\Application\UI\Form;

/////////////////////// FRONT: DEFAULT PRESENTER ///////////////////////

final class DefaultPresenter extends BasePresenter
{
	/** @var Model\ArticlesRepository */
	private $articles;

	public function __construct(
		Model\ArticlesRepository $articles
	)
	{
		$this->articles = $articles;
	}

	public function renderDefault()
	{
		$this->template->articles = $this->articles->findAll();
	}

	public function createComponentMessageForm(): Form {
		$form = new Form;

		$form->addTextArea('message')
			->setRequired()
			->setHtmlAttribute('class', 'msg__textarea')
			->setHtmlAttribute('placeholder', 'Write your message here...');

		$form->addSubmit('submit', 'Create message')
			->setHtmlAttribute('class', 'btn btn-primary');

		$form->addHidden('recaptcha_token');

		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	public function processForm(Form $form, $values) {
		$recaptcha = $this->verifyRecaptcha($values->recaptcha_token);
		if ($recaptcha->success && $recaptcha->score > 0.49) {
			$this->presenter->flashMessage($this->translator->translate('m.form.success'), 'success');
			$this->onSuccess($form);
		} else {
			$this->presenter->flashMessage($this->translator->translate('m.form.errorYouAreRobot'), 'error');
            $this->onError($form);
		}
	}
}
