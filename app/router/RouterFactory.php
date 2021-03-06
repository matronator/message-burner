<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
        ////////////////////////////////////////////////////////////
        //--------------------- ADMIN ROUTES ---------------------//
        ////////////////////////////////////////////////////////////
        $router->withModule('Admin')
            ->addRoute('admin/<presenter>/<action>[/<id>]', 'Default:default');

		////////////////////////////////////////////////////////////
		//--------------------- FRONT ROUTES ---------------------//
		////////////////////////////////////////////////////////////
		$router->withModule('Front')
			->addRoute('[<locale=cs cs|en>/]message/new/<hash>[/<isImage>]', 'Default:created')
			->addRoute('[<locale=cs cs|en>/]message/read/<hash>', 'Default:read')
			->addRoute('[<locale=cs cs|en>/]image/new', 'Default:image')
			->addRoute('[<locale=cs cs|en>/]image/read/<hash>', 'Default:readImage')
			->addRoute('[<locale=cs cs|en>/]image/delete/<hash>', 'Default:deleteImage')
			// SITEMAP
			->addRoute('sitemap.xml', 'Sitemap:default')
			->addRoute('sitemap', 'Sitemap:default')

			// MOST GENERAL ROUTE
			->addRoute('[<locale=cs cs|en>/]<presenter>/<action>[/<slug>]', 'Default:default');

		return $router;
	}

}
