<?php

namespace App\Service;

use App\Service\AppConfig;
use App\Service\AppDB;
use Pebble\ACLRole;

class AppACLRole
{

    /**
     * @var Pebble\ACLRole
     */
    public static $acl_role;

    /**
     * Return an Pebble\ACLRole instance
     */
    public function getACLRole()
    {
        if (self::$acl_role) return self::$acl_role;
        
        $config = (new AppConfig())->getConfig();
        $auth_cookie_settings = $config->getSection('Auth');
        $db = (new AppDB())->getDb();

        self::$acl_role = new ACLRole($db, $auth_cookie_settings);
        return self::$acl_role;
    }
}
