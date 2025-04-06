<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Services\HashService;
use App\Model\MessagesRepository;
use App\Services\EncryptionService;
use App\Services\ExpiryService;
use App\Services\Memes\MemeService;
use App\Services\PathService;
use DateTime;
use ImageStorage;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Tracy\Debugger;

final class DefaultPresenter extends BasePresenter
{
	/** @var MessagesRepository */
	private $messagesRepository;
	private $encryptionService;
	private $expiryService;

	/** @var ImageStorage */
	private $imageStorage;

	private PathService $pathService;

	public const PASSWORD_MIN_LENGTH = 3;
	public const IMAGE_DESCRIPTION_MAX_LENGTH = 1000;

	public function __construct(
		MessagesRepository $messagesRepository,
		EncryptionService $encryptionService,
		ExpiryService $expiryService,
		ImageStorage $imageStorage,
		PathService $pathService
	)
	{
		$this->messagesRepository = $messagesRepository;
		$this->encryptionService = $encryptionService;
		$this->expiryService = $expiryService;
		$this->imageStorage = $imageStorage;
		$this->pathService = $pathService;
	}

	public function renderDefault()
	{

	}

	public function renderDestroyed()
	{

	}

	public function renderImage()
	{
		$this->template->imageDescriptionMaxLength = self::IMAGE_DESCRIPTION_MAX_LENGTH;
	}

	public function renderCreated(string $hash = '', bool $isImage = false)
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$this->template->msgHash = $hash;
		if ($isImage) {
			$this->template->messageUrl = $this->link('//Default:readImage', $hash);
		} else {
			$this->template->messageUrl = $this->link('//Default:read', $hash);
		}
	}

	public function renderRead(string $hash = '')
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$message = $this->messagesRepository->getMessage($hash);
		if (!$message) {
			$this->template->noMessage = true;
			$this->template->meme = MemeService::getMeme();
		} else {
			$session = $this->session->getSection('readMessage');
			if ($this->isAjax()) {
				if ($session->get('showAndDelete') === true) {
					if ($session->get('withPassword') === true) {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => $this->encryptionService->decryptWithPassword($message->content, $session->get('password')),
						];
					} else {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => $this->encryptionService->decrypt($message->content),
						];
					}
					$this->messagesRepository->messageRead($hash);
					$session->remove('showAndDelete');
					$session->remove('withPassword');
					$session->remove('password');
				} else {
					if ($session->get('wrongPassword') === true) {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => '',
						];
						$this->template->msgError = $this->trans('general.errors.wrongPassword');
						$session->remove('wrongPassword');
					} else {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => '',
						];
						$this->template->msgError = $this->trans('general.errors.somethingWentWrong');
					}
				}
			} else {
				$this->template->message = (object) [
					'password' => $message->password,
					'content' => '',
				];
			}
		}
	}

	public function renderReadImage(string $hash = '')
	{
		if ($hash === '') {
			$this->redirect('default');
		}
		$this->template->hash = $hash;
		$message = $this->messagesRepository->getImage($hash);
		if (!$message) {
			$this->template->noMessage = true;
		} else {
			if ($this->isAjax()) {
				$session = $this->session->getSection('readMessage');
				if ($session->get('showAndDelete') === true) {
					$imagePath = $this->pathService->getWwwDir() . '/upload/messages/decrypted/' . $message->filename;
					$publicPath = $message->filename;
					$encryptedPath = $this->pathService->getWwwDir() . '/upload/messages/encrypted/' . $message->filename;
					$note = $message->note;
					if ($session->get('withPassword') === true) {
						$this->encryptionService->decryptFileWithPassword($encryptedPath, $imagePath, $session->get('password'));
						if ($message->note !== null) {
							$note = $this->encryptionService->decryptWithPassword($message->note, $session->get('password'));
						}
					} else {
						$this->encryptionService->decryptFile($encryptedPath, $imagePath);
						if ($message->note !== null) {
							$note = $this->encryptionService->decrypt($message->note);
						}
					}
					$this->template->message = (object) [
						'password' => $message->password,
						'content' => $message->filename,
						'fullPath' => $publicPath,
						'note' => $note !== null ? $note : '',
					];
					$this->messagesRepository->imageRead($hash);
					$session->remove('showAndDelete');
					$session->remove('withPassword');
					$session->remove('password');
				} else {
					if ($session->get('wrongPassword') === true) {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => '',
						];
						$this->template->msgError = $this->trans('general.errors.wrongPassword');
						$session->remove('wrongPassword');
					} else {
						$this->template->message = (object) [
							'password' => $message->password,
							'content' => '',
						];
						$this->template->msgError = $this->trans('general.errors.somethingWentWrong');
					}
				}
			} else {
				$this->template->message = (object) [
					'password' => $message->password,
					'content' => '',
				];
			}
		}
	}

	public function actionShowImage(string $path)
	{
		$path = $this->pathService->getWwwDir() . '/upload/messages/decrypted/' . $path;
		$image = Image::fromFile($path);
		$image->send();
		unlink($path);
	}

	// public function handleUnlockMessage()
	// {
	// 	$session = $this->session->getSection('readMessage');
	// 	// if ()
	// 	$session->set('showAndDelete', true);
	// 	$this->redrawControl('message');
	// }

	public function handleShowMessage()
	{
		$session = $this->session->getSection('readMessage');
		$session->set('showAndDelete', true);
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

	public function actionDestroy(string $hash)
	{
		$this->messagesRepository->getMessage($hash)->delete();
		$this->flashMessage($this->trans('general.success.messageDestroyed'), 'success');
		$this->redirect('Default:destroyed');
	}

	public function createComponentMessageForm(): Form
	{
		$form = new Form;

		$form->addTextArea('content')
			->setRequired()
			->setHtmlAttribute('placeholder', $this->trans('general.messageForm.messagePlaceholder'));
			// ->setHtmlAttribute('data-text-editor', 'message')
			// ->setHtmlAttribute('class', 'js-wysiwyg');

        // $form->addText('captcha', 'Enter captcha:');
		$form->addSelect('expiration', $this->trans('general.messageForm.expiration'), ExpiryService::EXPIRATION_OPTIONS)
			->setTranslator($this->translator)
			->setDefaultValue('twoDays');

        $form->addPassword('password', $this->trans('general.messageForm.password'))
            ->setHtmlAttribute('placeholder', $this->trans('general.messageForm.enterPassword'))
            ->addCondition(Form::Filled, true)
            ->addRule(Form::MinLength, "Password must be at least " . self::PASSWORD_MIN_LENGTH . " characters long.", self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('save', $this->trans('general.messageForm.send'));

		// $form->addHidden('recaptcha_token');

		$form->onSuccess[] = [$this, 'messageFormSucceeded'];
		return $form;
	}

	public function createComponentImageForm(): Form
	{
		$form = new Form;

		$form->addUpload('image', $this->trans('general.images.imageToSend'))
			->setRequired()
			->addRule($form::Image, $this->trans('general.errors.imageFormat'));

		$form->addTextArea('note')
			->setHtmlAttribute('maxlength', self::IMAGE_DESCRIPTION_MAX_LENGTH)
			->setHtmlAttribute('data-remaining-chars', self::IMAGE_DESCRIPTION_MAX_LENGTH)
			->setHtmlAttribute('placeholder', $this->trans('general.images.placeholder', ['max' => self::IMAGE_DESCRIPTION_MAX_LENGTH]));

		$form->addPassword('password', $this->trans('general.messageForm.password'))
            ->setHtmlAttribute('placeholder', $this->trans('general.messageForm.enterPassword'))
            ->addCondition(Form::Filled, true)
            ->addRule(Form::MinLength, "Password must be at least {self::PASSWORD_MIN_LENGTH} characters long.", self::PASSWORD_MIN_LENGTH);

		$form->addSelect('expiration', $this->trans('general.messageForm.expiration'), ExpiryService::EXPIRATION_OPTIONS)
			->setTranslator($this->translator)
			->setDefaultValue('twoDays');

		$form->addSubmit('save', $this->trans('general.images.sendImage'));

		$form->onSuccess[] = [$this, 'imageFormSucceeded'];
		return $form;
	}

	public function createComponentUnlockForm(): Form
	{
		$form = new Form;

		$form->setHtmlAttribute('data-ajax-parent', 'original_post');

		$form->addPassword('password', $this->trans('general.unlockForm.password'))
            ->setHtmlAttribute('placeholder', $this->trans('general.messageForm.enterPassword'))
			->setRequired();

		$form->addSubmit('send', $this->trans('general.unlockForm.unlockMessage'));

		$form->onSuccess[] = [$this, 'unlockFormSucceeded'];
		return $form;
	}

	public function messageFormSucceeded(Form $form, $values)
	{
		$data = [];
		$data['password'] = Strings::trim($values->password);
		$data['created_at'] = new DateTime();
		$data['expires_at'] = new DateTime('now ' . $this->expiryService::EXPIRATION_DATES[$values->expiration]);
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
		$this->flashMessage($this->trans('general.success.messageCreated'), 'success');
		$this->redirect('Default:created', $url);
	}

	public function imageFormSucceeded(Form $form, $values)
	{
		$data = [];
		$data['password'] = Strings::trim($values->password);
		$data['created_at'] = new DateTime();
		$data['expires_at'] = new DateTime('now ' . $this->expiryService::EXPIRATION_DATES[$values->expiration]);
		$noteTrimmed = Strings::substring($values->note, 0, self::IMAGE_DESCRIPTION_MAX_LENGTH);
		$url = '';

		if (strlen($data['password']) >= 3) {
			$hashedPassword = HashService::hashPassword($data['password']);
			if (isset($values->image) && $values->image->isOk()) {
				$savedFile = $this->imageStorage->saveImage($values->image, $this->messagesRepository->uploadDir);
				$data['filename'] = $savedFile->filename;
				$this->encryptionService->encryptFileWithPassword($savedFile->path, $savedFile->encryptPath, $data['password']);
			}
			if (Strings::length($noteTrimmed) > 0) {
				$data['note'] = $this->encryptionService->encryptWithPassword($noteTrimmed, $data['password']);
			}
			$data['password'] = $hashedPassword;
			$row = $this->messagesRepository->findAllImages()->insert($data);

			$hash = HashService::idToHash($row->id, 'images');
			$row->update(['hash' => $hash]);
		} else {
			unset($data['password']);
			if (isset($values->image) && $values->image->isOk()) {
				$savedFile = $this->imageStorage->saveImage($values->image, $this->messagesRepository->uploadDir);
				$data['filename'] = $savedFile->filename;
				$this->encryptionService->encryptFile($savedFile->path, $savedFile->encryptPath);
			}
			if (Strings::length($noteTrimmed) > 0) {
				$data['note'] = $this->encryptionService->encrypt($noteTrimmed);
			}
			$row = $this->messagesRepository->findAllImages()->insert($data);

			$hash = HashService::idToHash($row->id, 'images');
			$row->update(['hash' => $hash]);
		}

		$url = $hash;
		$this->flashMessage($this->trans('general.success.messageCreated'), 'success');
		$this->redirect('Default:created', $url, true);
	}

	public function unlockFormSucceeded(Form $form, $values)
	{
		if ($this->isLinkCurrent('Default:readImage')) {
			$message = $this->messagesRepository->getImage($this->getParameter('hash'));
		} else {
			$message = $this->messagesRepository->getMessage($this->getParameter('hash'));
		}
		$session = $this->session->getSection('readMessage');
		if (HashService::verifyPassword($values->password, $message->password)) {
			$session->set('showAndDelete', true);
			$session->set('withPassword', true);
			$session->set('wrongPassword', false);
			$session->set('password', $values->password);
		} else {
			$session->set('wrongPassword', true);
		}
		$this->redrawControl('messageContent');
	}
}
