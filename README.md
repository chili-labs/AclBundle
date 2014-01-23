# ProjectA AclBundle
###### Bundle for Symfony2 providing easy access to the complex ACL subsystem

[![Latest Stable Version](https://poser.pugx.org/project-a/acl-bundle/v/stable.png)](https://packagist.org/packages/project-a/acl-bundle) [![Latest Unstable Version](https://poser.pugx.org/project-a/acl-bundle/v/unstable.png)](https://packagist.org/packages/project-a/acl-bundle) [![Total Downloads](https://poser.pugx.org/project-a/acl-bundle/downloads.png)](https://packagist.org/packages/project-a/acl-bundle) [![Build Status](https://secure.travis-ci.org/project-a/AclBundle.png?branch=master)](http://travis-ci.org/project-a/AclBundle) [![Coverage Status](https://coveralls.io/repos/project-a/AclBundle/badge.png?branch=master)](https://coveralls.io/r/project-a/AclBundle?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/c73329cc-4028-463d-9228-afcfc3ebffbe/mini.png)](https://insight.sensiolabs.com/projects/c73329cc-4028-463d-9228-afcfc3ebffbe)

## Description

This Symfony 2 Bundle provides an easy api to the security-acl component and an eventlistener for automatic acl cleanup upon removal of domain objects. The installation is simple and by default does not change any behavior of your application.

Without this bundle you normaly do this (taken from the [ACL docs](http://symfony.com/doc/current/cookbook/security/acl.html#creating-an-acl-and-adding-an-ace)):
```php
// creating the ACL
$aclProvider = $this->get('security.acl.provider');
$objectIdentity = ObjectIdentity::fromDomainObject($domainObject);
$acl = $aclProvider->createAcl($objectIdentity);

// retrieving the security identity of the currently logged-in user
$securityContext = $this->get('security.context');
$user = $securityContext->getToken()->getUser();
$securityIdentity = UserSecurityIdentity::fromAccount($user);

// grant owner access
$acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
$aclProvider->updateAcl($acl);
```
With this bundle you can simplify it to:
```php
$securityContext = $this->get('security.context');
$user = $securityContext->getToken()->getUser();

$aclManager = $this->get('projecta_acl.manager');
$aclManager->manageObjectAces()
    ->grant($domainObject, MaskBuilder::MASK_OWNER, $user);
```
## Installation

The recommended way to install the AclBundle is [through
composer](http://getcomposer.org). Just create a `composer.json` file and
run the `php composer.phar install` command to install it:

    {
        "require": {
            "project-a/acl-bundle": "~1.0"
        }
    }

Alternatively, you can download the [`acl-bundle.zip`][1] file and extract it.

## Usage

WIP

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ php composer.phar install
    $ vendor/bin/phpunit

## License

ProjectA AclBundle is licensed under the MIT license.

## More about Project A Ventures

[www.project-a.com](http://www.project-a.com/en/working-with-project-a/)

[1]: https://github.com/project-a/AclBundle/archive/master.zip


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/project-a/aclbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

