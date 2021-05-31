<?php

declare(strict_types=1);

namespace App\Components;

use App\Model\MessagesRepository;
use Contributte\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class MessageForm extends Control
{
    private $translator;
    private $messagesRepository;
    public $onSuccess;
    public $onError;

    public function __construct(
        Translator $translator,
        MessagesRepository $messagesRepository
    )
    {
        $this->translator = $translator;
        $this->messagesRepository = $messagesRepository;
    }

    public function render($params = [])
    {
        $this->template->setParameters($params);
        $this->template->setFile(__DIR__ . '/MessageForm.latte');
        $this->template->render();
    }

    public function createComponentForm(): Form
    {
        $form = new Form;

		$form->addTextArea('content')
			->setRequired()
			->setHtmlAttribute('class', 'message-input')
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
