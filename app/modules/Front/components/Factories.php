<?php

declare(strict_types = 1);

namespace App\FrontModule\Factories;

use App\Components\MessageForm;
use \Contributte\Translation\Translator;
use App\Model\UserRepository;
use App\Model\ContactFormRepository;
use App\Model\MessagesRepository;

class AccountFormsFactory
{
	/** @var Translator */
	private $translator;

	/** @var UserRepository */
	private $userRepository;

	/** @var MessagesRepository */
	private $messagesRepository;

	public function __construct(
		Translator $translator,
		UserRepository $userRepository,
		MessagesRepository $messagesRepository
	)
	{
		$this->translator = $translator;
		$this->userRepository = $userRepository;
		$this->messagesRepository = $messagesRepository;
	}

	// public function createRegistrationForm()
	// {
	// 	return new \AccountForms\RegistrationForm($this->translator, $this->userRepository);
	// }

	// public function createChangePasswordForm()
	// {
	// 	return new \AccountForms\ChangePasswordForm($this->translator, $this->userRepository);
	// }

	// public function createPasswordRecoveryForm()
	// {
	// 	return new \AccountForms\PasswordRecoveryForm($this->translator, $this->userRepository);
	// }

	// public function createSetNewPasswordForm()
	// {
	// 	return new \AccountForms\SetNewPasswordForm($this->translator, $this->userRepository);
	// }

	// public function createLogInForm()
	// {
	// 	return new \AccountForms\LogInForm($this->translator, $this->userRepository);
	// }
}

class MessageFormFactory
{
	/** @var Translator */
	private $translator;

	/** @var MessagesRepository */
	private $messagesRepository;

	public function __construct(
		Translator $translator,
		MessagesRepository $messagesRepository
	)
	{
		$this->translator = $translator;
		$this->messagesRepository = $messagesRepository;
	}

	public function createMessageForm()
	{
		return new MessageForm($this->translator, $this->messagesRepository);
	}
}

class ContactFormFactory
{
	/** @var Translator */
	private $translator;

	/** @var ContactFormRepository */
	private $contactFormRepository;

	public function __construct(
		Translator $translator,
		ContactFormRepository $contactFormRepository
	)
	{
		$this->translator = $translator;
		$this->contactFormRepository = $contactFormRepository;
	}

	public function createContactForm()
	{
		return new \ContactForm($this->translator, $this->contactFormRepository);
	}
}
