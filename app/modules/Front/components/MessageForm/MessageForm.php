<?php

declare(strict_types=1);

namespace App\Components;

use App\Libs\HashService;
use App\Model\MessagesRepository;
use Contributte\Translation\Translator;
use DateTime;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

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

        // $form->addText('captcha', 'Enter captcha:');

        $form->addPassword('password', 'Protect message with password (Optional):')
            ->setHtmlAttribute('class', 'password-input')
            ->setHtmlAttribute('placeholder', '(Optional) password')
            ->addCondition(Form::FILLED, true)
            ->addRule(Form::MIN_LENGTH, 'Password must be at least 3 characters long.', 3);

		$form->addSubmit('submit', 'Create message')
			->setHtmlAttribute('class', 'btn btn-primary');

		// $form->addHidden('recaptcha_token');

		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
    }

    public function processForm(Form $form, $values)
    {
        $this->onSuccess();
	}
}
