<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Factories\MessageFormFactory;
use App\Model\MessagesRepository;
use Nette\Application\UI\Form;

final class DefaultPresenter extends BasePresenter
{
	/** @var MessagesRepository */
	private $messagesRepository;

	private $messageFormFactory;

	public function __construct(
		MessagesRepository $messagesRepository,
		MessageFormFactory $messageFormFactory
	)
	{
		$this->messagesRepository = $messagesRepository;
		$this->messageFormFactory = $messageFormFactory;
	}

	public function renderDefault()
	{

	}

	public function createComponentMessageForm()
	{
		$form = $this->messageFormFactory->createMessageForm();
		return $form;
	}
}
