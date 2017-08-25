.. index::
    pair: Global; Installation

Global Installation
===================

A global installation allows you to use the scaffold application everywhere.

.. code-block:: console

    composer global require aedart/scaffold

.. warning::

    If you have other packages installed globally, their dependencies might conflict.

    You can perhaps resolves this by making use of `Consolidation/Cgr <https://github.com/consolidation-org/cgr>`_, which safely installs each command line tool application in it's own directory.