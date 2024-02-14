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
			//EN
			->addRoute('[<locale=en en>/]message/new/<hash>[/<isImage>]', 'Default:created')
			->addRoute('[<locale=en en>/]message/read/<hash>', 'Default:read')
			->addRoute('[<locale=en en>/]message/delete/<hash>', 'Default:destroy')
			->addRoute('[<locale=en en>/]message/deleted', 'Default:destroyed')
			->addRoute('[<locale=en en>/]image/new', 'Default:image')
			->addRoute('[<locale=en en>/]image/read/<hash>', 'Default:readImage')

			// CS
			->addRoute('[<locale=cs cs>/]zprava/nova/<hash>[/<isImage>]', 'Default:created')
			->addRoute('[<locale=cs cs>/]zprava/precist/<hash>', 'Default:read')
			->addRoute('[<locale=cs cs>/]zprava/smazat/<hash>', 'Default:destroy')
			->addRoute('[<locale=cs cs>/]zprava/odstranena', 'Default:destroyed')
			->addRoute('[<locale=cs cs>/]obrazek/novy', 'Default:image')
			->addRoute('[<locale=cs cs>/]obrazek/otevrit/<hash>', 'Default:readImage')
			// SITEMAP
			->addRoute('sitemap.xml', 'Sitemap:default')
			->addRoute('sitemap', 'Sitemap:default')

			// MOST GENERAL ROUTE
			->addRoute('[<locale=en cs|en>/]<presenter>/<action>[/<slug>]', 'Default:default');

		return $router;
	}

}
