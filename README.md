# ProjectA AclBundle
###### Bundle for Symfony2 providing easy access to the complex ACL subsystem

[![Latest Stable Version](https://poser.pugx.org/project-a/acl-bundle/v/stable.png)](https://packagist.org/packages/project-a/acl-bundle)
[![Latest Unstable Version](https://poser.pugx.org/project-a/acl-bundle/v/unstable.png)](https://packagist.org/packages/project-a/acl-bundle)
[![Total Downloads](https://poser.pugx.org/project-a/acl-bundle/downloads.png)](https://packagist.org/packages/project-a/acl-bundle)
[![Build Status](https://secure.travis-ci.org/project-a/AclBundle.png?branch=master)](http://travis-ci.org/project-a/AclBundle)
[![Coverage Status](https://coveralls.io/repos/project-a/AclBundle/badge.png?branch=master)](https://coveralls.io/r/project-a/AclBundle?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c73329cc-4028-463d-9228-afcfc3ebffbe/mini.png)](https://insight.sensiolabs.com/projects/c73329cc-4028-463d-9228-afcfc3ebffbe)

## Description

This Symfony 2 Bundle provides an easy api to the Security/ACL component and an
eventlistener for automatic ACL cleanup upon removal of domain objects. The
installation is simple and by default does not change any behavior of your
application.

Without this bundle you normally do this (taken from the [ACL docs][1]):
```php
// creating the ACL
$aclProvider = $container->get('security.acl.provider');
$objectIdentity = ObjectIdentity::fromDomainObject($domainObject);
$acl = $aclProvider->createAcl($objectIdentity);

// retrieving the security identity of the currently logged-in user
$securityContext = $container->get('security.context');
$user = $securityContext->getToken()->getUser();
$securityIdentity = UserSecurityIdentity::fromAccount($user);

// grant owner access
$acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
$aclProvider->updateAcl($acl);
```
With this bundle you can simplify it to:

```php
$securityContext = $container->get('security.context');
$user = $securityContext->getToken()->getUser();

$aclManager = $container->get('projecta_acl.manager');
$aclManager->manageObjectAces()
    ->grant($domainObject, MaskBuilder::MASK_OWNER, $user);
```

## Documentation

The documentation is part of the bundle and can be found in Resources/doc.

1. [Installation](Resources/doc/installation.md)
2. [Working with the ACL Manager](Resources/doc/working_with_the_acl_manager.md)
    1. [Retrieving the ACL und ACE Managers](Resources/doc/working_with_aces.md#retrieving-the-acl-und-ace-managers)
    2. [Granting](Resources/doc/working_with_aces.md#granting)
    3. [Revoking](Resources/doc/working_with_aces.md#revoking)
    4. [Deleting ACLs](Resources/doc/working_with_aces.md#deleting-acls)
    5. [Preloading](Resources/doc/working_with_aces.md#preloading)
4. [Doctrine ACL cleanup eventlistener](Resources/doc/doctrine_acl_cleanup_eventlistener.md)
5. [Reference Guide](Resources/doc/reference_guide.md)
    1. [Configuration](Resources/doc/reference_guide.md#configuration)

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ composer install
    $ phpunit

## License

ProjectA AclBundle is licensed under the MIT license.

## More about Project A Ventures

[www.project-a.com](http://www.project-a.com/en/working-with-project-a/)

[1]: http://symfony.com/doc/current/cookbook/security/acl.html#creating-an-acl-and-adding-an-ace
