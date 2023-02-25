The `Pebble\ACLRole` class works almost like the `Pebble\ACL` class.  

The ACLRole class extends the ACL class so it is possible to use all public
methods found in the `Pebble\ACL` class and the `Pebble\Auth` class. 

An ACL role consist of a `right` and  a `auth_id`. 
The `right` could be `admin` or `read` and the `auth_id` is probably
the `auth_id` of a logged in user.  

Let's test the ACL object in a controller. 

```src/ACLRoleTestController.php ->```

~~~php
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

    #[Route('/role/add')]
    public function roleAdd()
    {
        $this->acl_role->setRole($this->role);
        echo "Access role added";
    }

    #[Route('/role/remove')]
    public function roleRemove()
    {
        $this->acl_role->removeRole($this->role);
        echo "Access rights removed";
    }

    #[Route('/admin/notes')]
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

~~~

We execute this controller in our `index.php` file: 

```examples/acl_role/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\ACLRoleTestController;
use Pebble\App\AppExec;
use Pebble\Router;
use Pebble\App\CommonUtils;

class TestApp {
    
    public function run() {

        $common_utils = new CommonUtils();
        $common_utils->setErrorHandler();
        
        $router = new Router();
        $router->addClass(ACLRoleTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();

~~~

Run this example using:

    php -S localhost:8000 -t examples/acl_role

You can now add the admin role on [http://localhost:8000/role/add](http://localhost:8000/role/add)

You can remove it on [http://localhost:8000/role/remove](http://localhost:8000/role/remove)

If the role exists then you may visit [http://localhost:8000/admin/notes](http://localhost:8000/admin/notes)


<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/900-ACLRole.md'>Edit this page on GitHub</a>