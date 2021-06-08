<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model;

class BasePresenter extends \App\BaseModule\Presenters\BasePresenter
{
	/** @var Model\PagesRepository */
	private $pages;

	public $contactFormFactory;

	public function injectRepository(
		Model\PagesRepository $pages
	)
	{
		$this->pages = $pages;
	}

	protected function beforeRender()
	{
		if ($this->isAjax()) {
			$this->redrawControl('body');
		}
		$this->template->pages = $this->pages->findAll();
		$this->template->urlAbsolutePath = $this->getURL()->hostUrl;
		$this->template->urlFullDomain = $this->getURL()->host;
		$this->template->defaultLocale = $this->defaultLocale;
	}

	public function handleChangeLocale(string $locale) {
		$this->redirect('this', ['locale' => $locale]);
	}
}
