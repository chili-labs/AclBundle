# Working with the ACL Manager

## Retrieving the ACL und ACE Managers

The ACL Manager is registered as service in the symfony DIC.
Simply retrieve the manager by doing the following.

```php
$aclManager = $container->get('projecta_acl.manager');
```

To get one of the ACE Managers you can either use the
methods ```manageObjectAces()``` and ```manageClassAces()``` or
retrieve them directly from the DIC. Whatever suits your needs.

```php
$objectAceManager = $container->get('projecta_acl.ace.objectmanager');
$classAceManager = $container->get('projecta_acl.ace.classmanager');
```

## ACL Manager

Besides the ability to get the two ACE managers you can also check
granted permissions for the current user. The method is
called ```isGranted()``` and
should explain it's functionality by itself.

```php
if ($aclManager->isGranted(MaskBuilder::MASK_EDIT, $object)) {
    // editing $object is allowed
}

if ($aclManager->isGranted(MaskBuilder::MASK_EDIT, $object, 'myfield')) {
    // editing $object->myfield is allowed
}
```

## ACE Manager

> Notice that the two AceManagers have a fluid interface. You can
> chain calls like
> this: ```$aceManager->grant(...)->grant(...)->revoke(...)```

### Granting

##### Object

You can grant rights on objects with one simple call to
the ```grant()``` method.

```php
$aceManager->grant($object, MaskBuilder::MASK_OWNER, $user);
```

This example marks ```$user``` as owner of exactly this
instance ```$object```.

##### Class

Granting rights for classes works the same as on objects.
When calling ```grant()``` on the ```ClassAceManager``` it will
grant ```$user``` rights on all instances from the class
of ```$object```.

##### Field

Granting rights to only a property of an object or class can also
be done with the same method. The only difference is to supply
the fourth parameter ```$field```.

```php
$aceManager->grant($object, MaskBuilder::MASK_OWNER, $user, 'myProperty');
```

### Revoking

##### Object

Removing previously granted rights can be done by calling
the ```revoke()``` method.

```php
$aceManager->revoke($object, MaskBuilder::MASK_OWNER, $user);
```

This example removes ```$user``` as owner of exactly this
instance ```$object```.

If you want to remove all granted rights on the object for one
user, there is a special method ```revokeAllForIdentity()```.

```php
$aceManager->revokeAllForIdentity($object, $user);
```

##### Class

Revoking rights for classes works the same as on objects.
When calling ```revoke()``` or ```revokeAllForIdentity()``` on
the ```ClassAceManager``` it will
revoke the previously granted rights for ```$user```  on all instances
from the class of ```$object```.

##### Field

If you want to revoke the granted rights for a field, you just need
to supply the 4th (```revoke()```) or 3rd (```revokeAllForIdentity```)
argument, which specifies the name of the field.

```php
$aceManager->revoke($object, MaskBuilder::MASK_OWNER, $user, 'myfield');
$aceManager->revokeAllForIdentity($object, $user, 'field');
```

### Deleting ACLs

Deleting the ACL for an ```$object``` is the same as if you would remove all
entries from the storage. This function is probably very useful when
you are going to remove the ```$object``` and want to cleanup all it's
ACL entries.

```php
$aceManager->deleteAcl($object);
```

### Preloading
