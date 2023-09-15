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
			->addRoute('[<locale=en cs|en>/]message/new/<hash>[/<isImage>]', 'Default:created')
			->addRoute('[<locale=en cs|en>/]message/read/<hash>', 'Default:read')
			->addRoute('[<locale=en cs|en>/]message/delete/<hash>', 'Default:destroy')
			->addRoute('[<locale=en cs|en>/]message/deleted', 'Default:destroyed')
			->addRoute('[<locale=en cs|en>/]image/new', 'Default:image')
			->addRoute('[<locale=en cs|en>/]image/read/<hash>', 'Default:readImage')
			// SITEMAP
			->addRoute('sitemap.xml', 'Sitemap:default')
			->addRoute('sitemap', 'Sitemap:default')

			// MOST GENERAL ROUTE
			->addRoute('[<locale=en cs|en>/]<presenter>/<action>[/<slug>]', 'Default:default');

		return $router;
	}

}
