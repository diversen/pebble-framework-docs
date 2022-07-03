## ACL

An access control list (ACL) is a list of rules that specifies which users or systems 
are granted or denied access to a particular object or system resource.

The ACL class extends the Auth class so it is possible to use all public
methods found in the Auth class. 

An ACL right consist of an `entity`, `entity_id`, `right`, and `auth_id`. 
The `entity` could be a database table named  **note**. The `entity_id` could be the primary ID
of the note table. The `right` could be `read` or `write`, and the `auth_id` is probably a 
logged in user's `auth_id`.   

Let's test the ACL object in a controller. 

(src/ACLTestController.php) -&gt;

~~~php
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

~~~

We execute this controller in our `index.php` file: 

(examples/acl/index.php) -&gt;

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\ACLTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();
        $router = new Router();
        $router->addClass(ACLTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();

~~~

Run this example using:

    php -S localhost:8000 -t examples/acl

You can now add the access right on [http://localhost:8000/rights/add](http://localhost:8000/rights/add)

You can remove it on [http://localhost:8000/rights/remove](http://localhost:8000/rights/remove)

If the right exists then you may visit [http://localhost:8000/note/read/42](http://localhost:8000/note/read/42)

But you can never visit [http://localhost:8000/note/read/41](http://localhost:8000/note/read/41)
(this ID can not be set)


<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/800-ACL.md'>Edit this page on GitHub</a>