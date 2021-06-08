<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

class BasePresenter extends \App\BaseModule\Presenters\BasePresenter
{
	protected function beforeRender()
	{
		if ($this->isAjax()) {
			$this->redrawControl('body');
		}
		$this->template->urlAbsolutePath = $this->getURL()->hostUrl;
		$this->template->urlFullDomain = $this->getURL()->host;
		$this->template->defaultLocale = $this->defaultLocale;
	}

	public function handleChangeLocale(string $locale) {
		$this->redirect('this', ['locale' => $locale]);
	}
}
