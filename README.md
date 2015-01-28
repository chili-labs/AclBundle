# ChiliLabs AclBundle
###### Bundle for Symfony2 providing easy access to the complex ACL subsystem

[![Latest Stable Version](https://img.shields.io/packagist/v/project-a/acl-bundle.svg?style=flat&label=stable)](https://packagist.org/packages/project-a/acl-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/project-a/acl-bundle.svg?style=flat)](https://packagist.org/packages/project-a/acl-bundle)
[![License](https://img.shields.io/packagist/l/project-a/acl-bundle.svg?style=flat)](https://packagist.org/packages/project-a/acl-bundle)
[![Build Status](https://secure.travis-ci.org/chili-labs/AclBundle.png?branch=master)](http://travis-ci.org/chili-labs/AclBundle)
[![Coverage Status](https://img.shields.io/coveralls/chili-labs/AclBundle.svg?style=flat)](https://coveralls.io/r/chili-labs/AclBundle?branch=master)
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

The latest documentation can be found on [aclbundle.chililabs.org](http://aclbundle.chililabs.org/en/latest/).

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ composer install
    $ phpunit

## License

ChiliLabs AclBundle is licensed under the MIT license.

[1]: http://symfony.com/doc/current/cookbook/security/acl.html#creating-an-acl-and-adding-an-ace
