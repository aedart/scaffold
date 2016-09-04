.. index::
    pair: BasePath; scaffold.php

BasePath
========

Location of scaffold's resources, e.g. static files and templates.

This path tells the scaffold where it can find it's resource, from which it can build and install one or several
folders, files and or templates.

.. code-block:: php

    <?php
    return [

        'basePath' => __DIR__ . '/resources/',

    ];