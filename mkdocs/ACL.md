An access control list (ACL) is a list of rules that specifies which users or systems 
are granted or denied access to a particular object or system resource.

The ACL class extends the Auth class so it is possible to use all public
methods found in the Auth class. 

An ACL right consist of an `entity`, `entity_id`, `right`, and `auth_id`. 
The `entity` could be a database table named  **note**. The `entity_id` could be the primary ID
of the note table. The `right` could be `read` or `write`, and the `auth_id` is probably a 
logged in user's `auth_id`.   

Let's test the ACL object in a controller. 

```src/ACLTestController.php ->```

~~~php
<?php

namespace App;

use Pebble\Service\ACLService;
use Exception;
use Pebble\Attributes\Route;
use Pebble\Router\Request;

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

    #[Route(path: '/rights/add')]
    public function RightsAdd()
    {
        $this->acl->setAccessRights($this->rights);
        echo "Access rights added";
    }

    #[Route(path: '/rights/remove')]
    public function rightsRemove()
    {
        $this->acl->removeAccessRights($this->rights);
        echo "Access rights removed";
    }

    #[Route(path: '/note/read/:id')]
    public function noteRead(Request $request)
    {
        $rights = [
            'entity' => 'note',
            'entity_id' => $request->param('id'),
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

```examples/acl/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\ACLTestController;
use Pebble\App\AppExec;
use Pebble\Router;
use Pebble\App\CommonUtils;

class TestApp {
    
    public function run() {

        $common_utils = new CommonUtils();   
        $common_utils->setErrorHandler();

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