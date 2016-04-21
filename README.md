#FormBundle

[![Build Status](https://secure.travis-ci.org/genemu/GenemuFormBundle.png)](https://travis-ci.org/genemu/GenemuFormBundle)

## Installation

Installation is quick and easy, 3 steps process

1. Install GenemuFormBundle
2. Enable the bundle
3. Initialize assets

### Step 1: Install GenemuFormBundle

Run the following command :

``` bash
$ composer require genemu/form-bundle "^3.0@dev"
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Genemu\Bundle\FormBundle\GenemuFormBundle(),
    );
}
```

### Step 3: Initialize assets

``` bash
$ php app/console assets:install web/
```

## Form types

### Select2 ([view demo](http://ivaynberg.github.com/select2/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/select2/index.md)

### Captcha GD

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/index.md)

### ReCaptcha ([Google library](http://www.google.com/recaptcha)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/recaptcha/index.md)

### JQueryUi ([download](http://jqueryui.com/)):

- [Autocomplete](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/text.md)

### Plain

A Form type that just renders the field as a p tag.
This is useful for forms where certain field need to be shown but not editable.

The type name is ``genemu_plain``.

## Tips

[Prototype usage within form collections](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/tips/form_prototype.md)

## Template

You use GenemuFormBundle and you seen that it does not work!
Maybe you have forgotten ``form_javascript`` or ``form_stylesheet``.

The principle is to separate the javascript, stylesheet and html. This allows better integration of web pages.

[View a template example form view](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/template.md)

## Note

There are maybe some bugs in those implementations, this package is just an idea of form types which can be very useful for your Symfony2 projects.
