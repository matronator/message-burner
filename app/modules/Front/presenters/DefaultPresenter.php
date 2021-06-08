<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Libs\HashService;
use App\Model\MessagesRepository;
use DateTime;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

final class DefaultPresenter extends BasePresenter
{
	/** @var MessagesRepository */
	private $messagesRepository;

	public function __construct(
		MessagesRepository $messagesRepository
	)
	{
		$this->messagesRepository = $messagesRepository;
	}

	public function renderDefault()
	{

	}

	public function renderCreated(string $hash = '')
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$this->template->hash = $hash;
		$this->template->messageUrl = $this->link('Default:read', $hash);
	}

	public function renderRead(string $hash = '')
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$message = $this->messagesRepository->getMessage($hash);
		if (!$message) {
			$this->redirect('default');
		}
		$this->template->url = $hash;
		$this->template->message = $message;
	}

	public function createComponentMessageForm(): Form
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

		$form->onSuccess[] = [$this, 'messageFormSucceeded'];
		return $form;
	}

	public function messageFormSucceeded(Form $form, $values)
	{
		$data = [];
		$data['password'] = Strings::trim($values->password);
		$data['created_at'] = new DateTime();
		$data['expires_at'] = new DateTime('now + 2 DAYS');
		$url = '';
		if (strlen($data['password']) >= 3) {
			$data['content'] = Crypto::encryptWithPassword($values->content, $data['password']);
			$row = $this->messagesRepository->findAll()->insert($data);
			$hash = HashService::idToHash($row->id);
			$row->update(['hash' => $hash]);
		} else {
			$key = Key::createNewRandomKey();
			$data['secret_key'] = $key->saveToAsciiSafeString();
			$data['content'] = Crypto::encrypt($values->content, $key);
			$row = $this->messagesRepository->findAll()->insert($data);
			$hash = HashService::idToHash($row->id);
			$row->update(['hash' => $hash]);
		}
		$url = $hash;
		$this->flashMessage('Message created!', 'success');
		$this->redirect('Default:created', $url);
	}
}
