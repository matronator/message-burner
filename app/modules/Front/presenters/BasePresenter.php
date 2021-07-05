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

	/**
	 * Asynchronously execute/include a PHP file. Does not record the output of the file anywhere.
	 *
	 * @param string $filename              file to execute, relative to calling script
	 * @param string $options               (optional) arguments to pass to file via the command line
	 */
	public function asyncInclude($filename, $options = '') {
		exec("/usr/local/opt/php@7.4/bin -f {$filename} {$options} >> /dev/null &");
	}
}
