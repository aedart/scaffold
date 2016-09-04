# Scaffold

[![Build Status](https://travis-ci.org/aedart/scaffold.svg?branch=master)](https://travis-ci.org/aedart/scaffold)
[![Latest Stable Version](https://poser.pugx.org/aedart/scaffold/v/stable)](https://packagist.org/packages/aedart/scaffold)
[![Total Downloads](https://poser.pugx.org/aedart/scaffold/downloads)](https://packagist.org/packages/aedart/scaffold)
[![Latest Unstable Version](https://poser.pugx.org/aedart/scaffold/v/unstable)](https://packagist.org/packages/aedart/scaffold)
[![License](https://poser.pugx.org/aedart/scaffold/license)](https://packagist.org/packages/aedart/scaffold)

How many times have you been doing the same task, over and over again? Maybe it's time for you to create some kind of helper that can perform that task for you. Perhaps this tool might be able to help you along the way.

**Scaffold** is a php-based command line tool for creating and installing various files and directories into your project, based on a configuration file (scaffold).

**Key features**

* Create desired directory structure
* Copy static files into desired location
* Ask user for input
* Generate files based on templates, compiled with data from the user 

In other words, you can perceive this tool as a console [wizzard](https://en.wikipedia.org/wiki/Wizard_(software)) for installing "something" inside your project.

## Contents

* [How to install](#how-to-install)
  * [Local](#local)
  * [Global](#global)
* [Run Demo](#run-demo)
* [Contribution](#contribution)
* [Acknowledgement](#acknowledgement)
* [Versioning](#versioning)
* [License](#license)

## How to install

This package uses [composer](https://getcomposer.org/). If you do not know what that is or how it works, I recommend that you read a little about, before attempting to use this package.

### Local

The Scaffold application is intended as a development tool, which is why it should be required via composer as a development dependency.

```console
composer require aedart/scaffold --dev
```

### Global

Alternatively, you can also require it globally and thereby making it available throughout many projects;

```console
composer global require aedart/scaffold
```

**Warning** If you have other packages installed globally, their dependencies might conflict with this package's dependencies or vise versa.
 
You can perhaps resolves this by making use of [Consolidation/Cgr](https://github.com/consolidation-org/cgr), which safely installs each command line tool application in it's own directory.

## Run Demo

Create a new directory and require the scaffold package as a development dependency (`require-dev`).

Afterwards, invoke the following command;

```console
vendor/bin/scaffold install -a
```

(_The `-a` option tells the install command to skip vendor and package selection and display the found scaffolds directly._)

On completion, the application will display a list of found scaffolds, starting by displaying the vendors you can choose from. Please follow these steps, in order to install an example (or demo) scaffold;

1. Select `Example Template (aedart/scaffold)` as the scaffold to install
2. Fill out the questions that you are asked, until completion

Once completed. you should see the following in your console;

```console
[OK] Example Template completed
```

In the directory that you are in, several directories has been created, a few static files have been copied into it and finally, some files have been generated based on your input. 

-----------------------------
## Contribution

Have you found a defect ( [bug or design flaw](https://en.wikipedia.org/wiki/Software_bug) ), or do you wish improvements? In the following sections, you might find some useful information
on how you can help this project. In any case, I thank you for taking the time to help me improve this project's deliverables and overall quality.

### Bug Report

If you are convinced that you have found a bug, then at the very least you should create a new issue. In that given issue, you should as a minimum describe the following;

* Where is the defect located
* A good, short and precise description of the defect (Why is it a defect)
* How to replicate the defect
* (_A possible solution for how to resolve the defect_)

When time permits it, I will review your issue and take action upon it.

### Fork, code and send pull-request

A good and well written bug report can help me a lot. Nevertheless, if you can or wish to resolve the defect by yourself, here is how you can do so;

* Fork this project
* Create a new local development branch for the given defect-fix
* Write your code / changes
* Create executable test-cases (prove that your changes are solid!)
* Commit and push your changes to your fork-repository
* Send a pull-request with your changes
* _Drink a [Beer](https://en.wikipedia.org/wiki/Beer) - you earned it_ :)

As soon as I receive the pull-request (_and have time for it_), I will review your changes and merge them into this project. If not, I will inform you why I choose not to.

## Acknowledgement

* [ Laravel ](https://laravel.com), `Taylor Otwell`; and especially for his [Service Container](https://laravel.com/docs/master/container), that I'm using daily
* [ Symfony ](http://symfony.com/), `Fabien Potencier`; not sure how I would be writing console commands without the Symfony Console component!
* [ Laracasts ](https://laracasts.com/), `Jeffrey Way et al.`; worth every penny…
* [ PHPUnit ](https://phpunit.de/), `Sebastian Bergmann`; By the gods ... I still know some developers, managers and decision makers, that believe good software does not require testing, at all! I cannot image working on any project, without good testing tools, such as PHPUnit.
* [ Codeception ](http://codeception.com/), `Michael Bodnarchuk`; for making PHPUnit even better.
* [ Composer ](https://getcomposer.org/) & [ Packagist ](https://packagist.org/), `Nils Adermann, Jordi Boggiano & et al.`; amongst the best things that has happened to the PHP community.
* [ Git ](http://git-scm.com/), `Software Freedom Conservancy`; without it… We would still be stuck in the "stone age" of software development.
* [ PHP ](http://php.net/), `Rasmus Lerdorf & The PHP Group`; we might be developing in old fashioned ASP… (Shivers!)
* [ PHPStorm ](https://www.jetbrains.com/phpstorm/), `Jetbrains`; for developing the best PHP-IDE, and supporting this and other [OpenSource](http://en.wikipedia.org/wiki/Open_source) projects

## Versioning

This package follows [Semantic Versioning 2.0.0](http://semver.org/)

## License

[BSD-3-Clause](http://spdx.org/licenses/BSD-3-Clause), Read the LICENSE file included in this package
