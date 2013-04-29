#FormBundle

[![Build Status](https://secure.travis-ci.org/genemu/GenemuFormBundle.png)](https://travis-ci.org/genemu/GenemuFormBundle)

## Installation

Installation is quick and easy, 3 steps process

1. Install GenemuFormBundle
2. Enable the bundle
3. Initialize assets

### Step 1: Install GenemuFormBundle

Add the following dependency to your composer.json file:
``` json
{
    "require": {
        "_some_packages": "...",

        "genemu/form-bundle": "2.1.*" => for Symfony 2.1 and 2.2

        "genemu/form-bundle": "2.2.*" => for Symfony 2.3
    }
}
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

### Tinymce ([download](http://www.tinymce.com/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/index.md)

### JQueryUi ([download](http://jqueryui.com/)):

- [Datepicker](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/datepicker/index.md)
- [AddressPicker](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/geolocation/index.md)
- [Slider](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/slider/index.md)
- [Autocomplete](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/text.md)

### File ([uploadify library](http://www.uploadify.com)):

You can use [jcrop](http://deepliquid.com/content/Jcrop.html) to uploadify.
You send the image and crop or apply filter.

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/index.md)

### Image ([view demo](http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/image/index.md)

### Colorpicker ([view demo](http://www.eyecon.ro/colorpicker/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/color/index.md)

### Rating ([view demo](http://www.fyneworks.com/jquery/star-rating/#tab-Testing)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/rating/index.md)

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
