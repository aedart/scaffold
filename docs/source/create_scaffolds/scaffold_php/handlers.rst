.. index::
    pair: Handlers; scaffold.php

Handlers
========

======== =======
required no
======== =======

These handlers are used by some of the default tasks. They provide an additional level of flexibility, in which you
can use the default tasks, yet change the way that each of them handles certain operations.

If you do not plan to change the default tasks' behaviour, then you can leave out this part of the configuration.

.. code-block:: php

    <?php
    return [
        'handlers' => [
            'directory'     =>    \Aedart\Scaffold\Handlers\DirectoriesHandler::class,
            'file'          =>    \Aedart\Scaffold\Handlers\FilesHandler::class,
            'property'      =>    \Aedart\Scaffold\Handlers\PropertyHandler::class,
            'template'      =>    \Aedart\Scaffold\Handlers\TwigTemplateHandler::class,
            'script'        =>    \Aedart\Scaffold\Handlers\ScriptsHandler::class,
        ]
    ];

.. note::

    You can create your own handlers, if the default handlers are insufficient to cover your needs.

    Handlers need to implement the :code:`\Aedart\Scaffold\Contracts\Handlers\Handler` interface.

    Alternatively, you can extend the :code:`\Aedart\Scaffold\Handlers\BaseHandler` abstract handler.
    It should provide you with a few common methods implementation.

.. tip::

    Currently, only `Twig <http://twig.sensiolabs.org/>`_ templates are supported. If you do not
    like working with that engine, then you can implement a different one, by means of creating a new template
    handler (Please review :code:`\Aedart\Scaffold\Handlers\TwigTemplateHandler` for inspiration).

    If this is the case, please consider if making that handler publicly available for others :)