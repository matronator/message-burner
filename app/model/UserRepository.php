<?php

namespace App\Model;

use Nette;


class UserRepository
{
    /** @var Nette\Database\Explorer */
    private $database;

    public $roles = [
        'a' => 'Admin',
        'u' => 'User'
    ];

    private $navItems = [
        [
            'presenter' => 'Gallery',
            'title' => 'Galerie',
            'icon' => 'album'
        ],
        [
            'presenter' => 'User',
            'title' => 'Uživatelé',
            'icon' => 'users'
        ],
    ];

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }

    public function findAll()
    {
        return $this->database->table('user');
    }

    public function getNavItems()
    {
        return array_map(function($item) {
            return (object) $item;
        }, $this->navItems);
    }
}
