Basic Usage
===========

.. note::

    Notice that the two AceManagers have a fluid interface. You can
    chain calls like this:

    .. code-block:: php

        $aceManager->grant(...)->grant(...)->revoke(...);
        
.. note::
    The ClassAceManager also accepts the classname as parameter, in case
    you don't have an instance of the class.

    .. code-block:: php

        $aceManager->grant(MyObject::class, ...);

Granting permissions
--------------------

Object
~~~~~~

You can grant rights on objects with one simple call to the ``grant()``
method.

.. code-block:: php

    $aceManager->grant($object, MaskBuilder::MASK_OWNER, $user);

This example marks ``$user`` as owner of exactly this instance
``$object``.

Class
~~~~~

Granting rights for classes works the same as on objects. When calling
``grant()`` on the ``ClassAceManager`` it will grant ``$user`` rights on
all instances from the class of ``$object``.

Field
~~~~~

Granting rights to only a property of an object or class can also be
done with the same method. The only difference is to supply the fourth
parameter ``$field``.

.. code-block:: php

    $aceManager->grant($object, MaskBuilder::MASK_OWNER, $user, 'myProperty');

Revoking permissions
--------------------

Object
~~~~~~

Removing previously granted rights can be done by calling the
``revoke()`` method.

.. code-block:: php

    $aceManager->revoke($object, MaskBuilder::MASK_OWNER, $user);

This example removes ``$user`` as owner of exactly this instance
``$object``.

If you want to remove all granted rights on the object for one user,
there is a special method ``revokeAllForIdentity()``.

.. code-block:: php

    $aceManager->revokeAllForIdentity($object, $user);

Class
~~~~~

Revoking rights for classes works the same as on objects. When calling
``revoke()`` or ``revokeAllForIdentity()`` on the ``ClassAceManager`` it
will revoke the previously granted rights for ``$user`` on all instances
from the class of ``$object``.

Field
~~~~~

If you want to revoke the granted rights for a field, you just need to
supply the 4th (``revoke()``) or 3rd (``revokeAllForIdentity``)
argument, which specifies the name of the field.

.. code-block:: php

    $aceManager->revoke($object, MaskBuilder::MASK_OWNER, $user, 'myfield');
    $aceManager->revokeAllForIdentity($object, $user, 'field');

Checking permissions
--------------------

If you want to check if the current user is granted a permission you can
do that by calling ``isGranted()``.

.. code-block:: php

    if ($aclManager->isGranted(MaskBuilder::MASK_EDIT, $object)) {
        // editing $object is allowed
    }

    if ($aclManager->isGranted(MaskBuilder::MASK_EDIT, $object, 'myfield')) {
        // editing $object->myfield is allowed
    }


Deleting ACLs
-------------

Deleting the ACL for an ``$object`` is the same as if you would remove
all entries from the storage. This function is probably very useful when
you are going to remove the ``$object`` and want to cleanup all it's ACL
entries.

.. code-block:: php

    $aceManager->deleteAcl($object);

.. note::
    If you want to automate the deletion of all acl entries for an domain object
    when it gets deleted have a look at :doc:`doctrine_cleanup_listener`
