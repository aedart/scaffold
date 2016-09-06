.. index::
    single: scaffold.php

(*).scaffold.php
================

The :code:`*.scaffold.php` file is the configuration file (a php array) that the scaffold application will read and
execute upon installation. In short, it determines what will happen when an end-user installs it.

.. code-block:: php

    <?php
    return [

        'name'          => 'My Scaffold',

        'description'   => 'Creates cool stuff',

        'basePath' => __DIR__ . '/resources/',

        'tasks' => [],

        'folders' => [],

        'files' => [],

        'templateData' => [],

        'templates' => [],

        'handlers' => [],
    ];

In the following sections, each of the above properties is covered in detail.

.. toctree::
   :maxdepth: 2

   name
   description
   base-path
   tasks
   folders
   files
   template-data
   templates
   handlers
