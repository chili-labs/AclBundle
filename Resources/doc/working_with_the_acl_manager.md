# Working with the ACL Manager

## Getting the ACL und ACE Managers

## Granting

#### Object

You can grant rights on objects with one simple call to the ```grant()``` method.

```php
$aceManager->grant($object, MaskBuilder::MASK_OWNER, $user);
```

This example marks ```$user``` as owner of exactly this instance ```$object```.

#### Class

Granting rights on classes works the same as on objects. When calling ```grant()``` on the ```ClassAceManager``` it will grant ```$user``` rights on all instances from the class of ```$object```.

#### Field

Granting rights to only a property of an object or class can also be done with the same method. The only difference is to supply the fourth parameter ```$field```.

```php
$aceManager->grant($object, MaskBuilder::MASK_OWNER, $user, 'myProperty');
```

## Revoking

## Deleting ACLs

## Preloading