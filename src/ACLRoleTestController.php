<?php

namespace App;

use Pebble\Service\ACLRoleService;
use Exception;
use Pebble\Attributes\Route;

class ACLRoleTestController
{

    private $acl_role;
    private $role = [];
    public function __construct()
    {
        // Get acl role instance using the service class
        $this->acl_role = (new ACLRoleService())->getACLRole();

        // Under normal circumstances you would receive an auth_id 
        // from the ACLRole object using `$this->acl_role->getAuthId();`
        // when the user is in session
        $this->role =
            [
                'right' => 'admin',
                'auth_id' => '1'
            ];
    }

    #[Route(path: '/role/add')]
    public function roleAdd()
    {
        $this->acl_role->setRole($this->role);
        echo "Access role added";
    }

    #[Route(path: '/role/remove')]
    public function roleRemove()
    {
        $this->acl_role->removeRole($this->role);
        echo "Access rights removed";
    }

    #[Route(path: '/admin/notes')]
    public function noteRead(array $params)
    {
        $role = [
            'right' => 'admin',
            // Normally you would use `$this->acl_role->getAuthId();`
            'auth_id' => 1, 
        ];

        try {
            $this->acl_role->hasRoleOrThrow($role);
            echo "You have the admin role. You have access to /admin/notes";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
