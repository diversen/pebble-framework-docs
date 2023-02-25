<?php

namespace App;

use Pebble\Service\ACLService;
use Exception;
use Pebble\Attributes\Route;

class ACLTestController
{

    private $acl;
    private $rights = [];
    public function __construct()
    {
        $this->acl = (new ACLService())->getACL();

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

    #[Route('/rights/add')]
    public function RightsAdd()
    {
        $this->acl->setAccessRights($this->rights);
        echo "Access rights added";
    }

    #[Route('/rights/remove')]
    public function rightsRemove()
    {
        $this->acl->removeAccessRights($this->rights);
        echo "Access rights removed";
    }

    #[Route('/note/read/:id')]
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
