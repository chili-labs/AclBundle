Welcome to the AclBundle Documentation!
=======================================

Requirements
------------

The AclBundle has only a few dependencies that need to be fulfilled. It
requires PHP version 5.3, as namespaces are used and the following composer
packages:

-  doctrine/common with at least version 2.2
-  symfony/security with at least version 2.3

.. note::

    It might also work with earlier versions of these packages, but was not tested with
    them. If you get it working with earlier versions, feel free to open
    a pull request.

Installation
------------

Installation is done in two steps like any other Bundle.

1. Download the bundle
~~~~~~~~~~~~~~~~~~~~~~

The recommended way to install the AclBundle is through `Composer`_.
Just run the following command to get the latest version:

.. code-block:: bash

    $ composer require project-a/acl-bundle

2. Enable the bundle
~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel of your application:

.. code-block:: php

    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new ProjectA\Bundle\AclBundle\ProjectAAclBundle(),
        );
    }

.. note::

    After enabling the Bundle in your kernel you will not notice any
    difference, as using the provided services is optional and the
    eventlistener for doctrine is not enabled by default.

This was already everything you need to do to get started with the
AclBundle.

Guides
------

Learn more about how to use this bundle.

.. toctree::
    :maxdepth: 2

    retrieving_service_classes
    basic_usage
    doctrine_cleanup_listener
    configuration_reference

.. _Composer: https://getcomposer.org
