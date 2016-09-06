.. index::
    triple: Location; Empty; Scaffold
    pair: Location; Scaffold
    single: scaffold.php

Location
========

The first thing you need to consider is, where to place your scaffold files.

Regardless of your choice, I recommend that you use a `version control system <https://en.wikipedia.org/wiki/Version_control>`_ !

.. note::

    Make sure that the scaffold application is available a development dependency.

    See :doc:`../how_to_install/index`

New Project
^^^^^^^^^^^

The "safest" options is to create a new repository in which you only have to deal with the scaffold and it's
resources. Doing so allows you to focus mainly on the structure of the scaffold and there is little risk for
you to interfere with existing files.

.. tip::

    If you are new to using this application, then I recommend that you start off by creating scaffolds
    in a separate project.

Existing Project
^^^^^^^^^^^^^^^^

Alternatively, you can also create scaffolds directly into existing projects. This does have the benefit of having
a tight coupling between your project and associated scaffolds. However, be sure of what you are doing, as it is the
nature of this application to write files!

Location of (*).scaffold.php
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The :code:`*.scaffold.php` file is covered in the upcoming chapters. For now, you just need to ensure that it is located
in the project's root directory, at the same level as your :code:`composer.json`.

The reason why the scaffold configuration file (:code:`*.scaffold.php`) needs to be located here, is due to the indexing
performed by the application. By default, it will search for scaffolds inside the local vendor directory, searching
each package's root directory only!