Flamework
=========

Yet another MVC framework for PHP 5 aiming to import new features of PHP into practice.

Features
--------

  - Use namespace to get rid of naming conflicts.
  - Class auto-loading, no more include()s or require()s are needed.
  - Error auto-handling, exceptions and errors are handled by default handlers, if no custom handler exists.
  - Filters, for decoupling requests handling and action executing.
  - Component based, to make the framework core slim and high extensible.
  - Lazy initializing, resources (such as database connections, classes, components, etc.) are only initialized when they are called.
  - Parameter binding, access request parameters as method parameters in actions, forget ugly $_POST, $_GET arrays.

Requirements
------------

  - PHP 5.3 or above

Author & License
----------------

  - Author: Donie Leigh <donie.leigh at gmail.com>
  - Website: http://0x3f.org
  - License: BSD License (3 terms edition)
