![logo](http://eden.openovate.com/assets/images/cloud-social.png) Eden Handler
====
[![Build Status](https://api.travis-ci.org/Eden-PHP/Handler.png)](https://travis-ci.org/Eden-PHP/Handler)
====
 
- [Install](#install)
- [Introduction](#intro)
- [API](#api)
- [Contributing](#contributing)

====

<a name="install"></a>
## Install

`composer install eden/handler`

====

<a name="intro"></a>
## Introduction

Allows errors and exceptions to be event driven.

====

<a name="api"></a>
## API

====

### Registering the Exception Handler

Adds an event driven way to respond to exceptions.

#### Example

```
eden('handler')
	->exception()
	->register()
	->on('exception', function(
		$type,
		$level,
        $reporter,
		$file,
		$line,
        $message,
		$trace
	) {
		echo 'An exception has occurred';
	});
```

====

### Releasing the Exception Handler

#### Example

```
eden('handler')->exception()->release();
```

====

### Registering the Error Handler

Adds an event driven way to respond to PHP errors.

#### Example

```
eden('handler')
	->error()
	->register()
	->on('error', function(
		$type,
		$level,
        $reporter,
		$file,
		$line,
        $message,
		$trace
	) {
		echo 'An error has occurred';
	});
```

====

### Releasing the Error Handler

#### Example

```
eden('handler')->error()->release();
```

====

<a name="contributing"></a>
#Contributing to Eden

Contributions to *Eden* are following the Github work flow. Please read up before contributing.

##Setting up your machine with the Eden repository and your fork

1. Fork the repository
2. Fire up your local terminal create a new branch from the `v4` branch of your 
fork with a branch name describing what your changes are. 
 Possible branch name types:
    - bugfix
    - feature
    - improvement
3. Make your changes. Always make sure to sign-off (-s) on all commits made (git commit -s -m "Commit message")

##Making pull requests

1. Please ensure to run `phpunit` before making a pull request.
2. Push your code to your remote forked version.
3. Go back to your forked version on GitHub and submit a pull request.
4. An Eden developer will review your code and merge it in when it has been classified as suitable.