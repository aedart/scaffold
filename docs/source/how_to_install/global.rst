.. index::
    pair: Global; Installation

Global Installation
===================

The Scaffold application is intended as a development tool, which is why it should be required via composer as a development dependency.

.. code-block:: console

    composer global require aedart/scaffold

.. warning::

    If you have other packages installed globally, their dependencies might conflict.

    You can perhaps resolves this by making use of `Consolidation/Cgr <https://github.com/consolidation-org/cgr>`_, which safely installs each command line tool application in it's own directory.