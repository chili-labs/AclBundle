Retrieving Service Classes
==========================

The AclBundle registeres 3 service classes in the DependencyInjection Container,
the ``AclManager``, the ``ClassAceManager`` and the ``ObjectAceManager``.

Acl Manager
-----------

To retrieve the AclManager simply do this:

.. code-block:: php

    $aclManager = $container->get('projecta_acl.manager');

The AclManager has 2 public methods to retrieve the Ace Managers:

:manageObjectAces(): returns the ObjectAceManager

:manageClassAces(): returns the ClassAceManager

Class/Object Ace Manager
------------------------

To get one of the ACE Managers you can either use the methods
``manageObjectAces()`` and ``manageClassAces()`` (see above) or retrieve them
directly from the DIC. Whatever suits your needs best.

.. code-block:: php

    $objectAceManager = $container->get('projecta_acl.ace.objectmanager');
    $classAceManager = $container->get('projecta_acl.ace.classmanager');
