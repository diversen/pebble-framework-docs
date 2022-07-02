<?php

namespace App;

use Pebble\Service\ACLRoleService;
use Pebble\App\AppBase;
use Exception;

class ACLRoleTestController
{

    private $acl_role;
    private $role = [];
    public function __construct()
    {
        // Get acl role instance using the service class
        $this->acl_role = (new ACLRoleService())->getACLRole();

        // Get acl role instance using the AppBase class
        $this->acl_role = (new AppBase())->getACLRole();

        // Under normal circumstances you would receive an auth_id 
        // from the ACLRole object using `$this->acl_role->getAuthId();`
        // when the user is in session
        $this->role =
            [
                'right' => 'admin',
                'auth_id' => '1'
            ];
    }

    /**
     * @route /role/add
     * @verbs GET
     */
    public function roleAdd()
    {
        $this->acl_role->setRole($this->role);
        echo "Access role added";
    }

    /**
     * @route /role/remove
     * @verbs GET
     */
    public function roleRemove()
    {
        $this->acl_role->removeRole($this->role);
        echo "Access rights removed";
    }

    /**
     * @route /admin/notes
     * @verbs GET
     */
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
