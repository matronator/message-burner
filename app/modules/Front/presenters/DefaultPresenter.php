<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Services\HashService;
use App\Model\MessagesRepository;
use App\Services\EncryptionService;
use App\Services\ExpiryService;
use DateTime;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

final class DefaultPresenter extends BasePresenter
{
	/** @var MessagesRepository */
	private $messagesRepository;
	private $encryptionService;
	private $expiryService;

	public function __construct(
		MessagesRepository $messagesRepository,
		EncryptionService $encryptionService,
		ExpiryService $expiryService
	)
	{
		$this->messagesRepository = $messagesRepository;
		$this->encryptionService = $encryptionService;
		$this->expiryService = $expiryService;
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
		$this->template->messageUrl = $this->link('//Default:read', $hash);
	}

	public function renderRead(string $hash = '')
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$message = $this->messagesRepository->getMessage($hash);
		if (!$message) {
			$this->template->noMessage = true;
		} else {
			if ($this->isAjax()) {
				$session = $this->session->getSection('readMessage');
				if ($session['showAndDelete'] === true) {
					$this->template->message = (object) [
						'password' => $message->password,
						'content' => $this->encryptionService->decrypt($message->content),
					];
					$this->messagesRepository->messageRead($hash);
					unset($session['showAndDelete']);
				} else {
					$this->template->message = (object) [
						'password' => $message->password,
						'content' => '',
					];
					$this->template->msgError = 'Something went wrong';
				}
			} else {
				$this->template->message = (object) [
					'password' => $message->password,
					'content' => '',
				];
			}
		}
	}

	public function handleTest()
	{
		$this->template->test = 'Test';
		$this->redrawControl('test');
	}

	public function handleUnlockMessage()
	{

	}

	public function handleShowMessage()
	{
		$session = $this->session->getSection('readMessage');
		$session['showAndDelete'] = true;
		$this->redrawControl('message');
	}

	public function actionExpireMessages(?string $hash = null, ?string $confirm = null)
	{
		if ($hash === null || $confirm === null) {
			$this->sendJson(false);
		}
		$correct = $this->expiryService->verifyExpiration($hash, $confirm);
		if ($correct) {
			$this->messagesRepository->deleteExpiredMessages();
			$this->sendJson(true);
		}
		$this->sendJson(false);
	}

	public function createComponentMessageForm(): Form
	{
		$form = new Form;

		$form->addTextArea('content')
			->setRequired()
			->setHtmlAttribute('placeholder', 'Write your message here...');
			// ->setHtmlAttribute('data-text-editor', 'message')
			// ->setHtmlAttribute('class', 'js-wysiwyg');

        // $form->addText('captcha', 'Enter captcha:');

        $form->addPassword('password', 'Password (optional):')
            ->setHtmlAttribute('placeholder', 'Enter password')
            ->addCondition(Form::FILLED, true)
            ->addRule(Form::MIN_LENGTH, 'Password must be at least 3 characters long.', 3);

		$form->addSubmit('save', 'Create message');

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
			$hashedPassword = HashService::hashPassword($data['password']);
			$data['content'] = $this->encryptionService->encryptWithPassword($values->content, $data['password']);
			$data['password'] = $hashedPassword;
			$row = $this->messagesRepository->findAll()->insert($data);
			$hash = HashService::idToHash($row->id);
			$row->update(['hash' => $hash]);
		} else {
			unset($data['password']);
			// $data['secret_key'] = $this->encryptionService->key;
			$data['content'] = $this->encryptionService->encrypt($values->content);
			$row = $this->messagesRepository->findAll()->insert($data);
			$hash = HashService::idToHash($row->id);
			$row->update(['hash' => $hash]);
		}
		$url = $hash;
		$this->flashMessage('Message created!', 'success');
		$this->redirect('Default:created', $url);
	}
}
