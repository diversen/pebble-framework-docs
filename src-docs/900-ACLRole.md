The `Pebble\ACLRole` class works almost like the `Pebble\ACL` class.  

The ACLRole class extends the ACL class so it is possible to use all public
methods found in the `Pebble\ACL` class and the `Pebble\Auth` class. 

An ACL role consist of a `right` and  a `auth_id`. 
The `right` could be `admin` or `read` and the `auth_id` is probably
the `auth_id` of a logged in user.  

Let's test the ACL object in a controller. 

<!-- include: src/ACLRoleTestController.php -->

We execute this controller in our `index.php` file: 

<!-- include: examples/acl_role/index.php -->

Run this example using:

    php -S localhost:8000 -t examples/acl_role

You can now add the admin role on [http://localhost:8000/role/add](http://localhost:8000/role/add)

You can remove it on [http://localhost:8000/role/remove](http://localhost:8000/role/remove)

If the role exists then you may visit [http://localhost:8000/admin/notes](http://localhost:8000/admin/notes)
