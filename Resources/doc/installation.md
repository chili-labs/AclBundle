# Installation

## Requirements

The AclBundle has only a few dependencies that need to be fulfilled. It requires PHP version 5.3 as namespaces are used.
The required composer packages are as follows:

* doctrine/common with at least version 2.2
* symfony/security with at least version 2.3

> It might also work with earlier versions, but was not tested with them. If you get it working with earlier versions, feel free to open a pull request.


## Installation

Installation is done in two steps like any other Bundle.

### 1. Download the bundle

The recommended way to install the AclBundle is [through composer][1]. Just create a `composer.json` file and run the `composer install` command to install it:

```json
{
    "require": {
        "project-a/acl-bundle": "~1.0"
    }
}
```

### 2. Enable the bundle

Enable the bundle in the kernel of your application:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new ProjectA\Bundle\AclBundle\ProjectAAclBundle(),
    );
}
```

> After enabling the Bundle in your kernel you will not notice any difference, as using the ```AclManager```is optional and the eventlistener for doctrine is not enabled by default.

This is already everything you need to start using this bundle.

## Next steps

[Working with the ACL Manager](working_with_the_acl_manager.md) - See how to use the manager
[Doctrine ACL cleanup eventlistener](doctrine_acl_cleanup_eventlistener.md) - Add automatic cleanup of ACLs upon removing of mapped doctrine objects

[1]: http://getcomposer.org
