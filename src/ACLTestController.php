<?php

namespace App;

use Pebble\Service\ACLService;
use Pebble\App\AppBase;
use Exception;

class ACLTestController
{

    private $acl;
    private $rights = [];
    public function __construct()
    {
        $this->acl = (new ACLService())->getACL();

        // Or
        $this->acl = (new AppBase())->getACL();

        // Under normal circumstances you would receive a auth_id 
        // from the ACL object using `$this->acl->getAuthId();`
        // When the user is logged in. 
        $this->rights =
            [
                'entity' => 'note',
                'entity_id' => 42,
                'right' => 'read',
                'auth_id' => 1,
            ];
    }


    /**
     * @route /rights/add
     * @verbs GET
     */
    public function RightsAdd()
    {
        $this->acl->setAccessRights($this->rights);
        echo "Access rights added";
    }

    /**
     * @route /rights/remove
     * @verbs GET
     */
    public function rightsRemove()
    {
        $this->acl->removeAccessRights($this->rights);
        echo "Access rights removed";
    }

    /**
     * @route /note/read/:id
     * @verbs GET
     */
    public function noteRead(array $params)
    {
        $rights = [
            'entity' => 'note',
            'entity_id' => $params['id'],
            'right' => 'read',
            'auth_id' => 1,
        ];

        try {
            $this->acl->hasAccessRightsOrThrow($rights);
            echo "You can see the secret note 42";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }
}
