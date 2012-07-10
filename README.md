#FormBundle

[![Build Status](https://secure.travis-ci.org/genemu/GenemuFormBundle.png)](https://secure.travis-ci.org/genemu/GenemuFormBundle)

## Installation

Installation is quick and easy, 5 steps process

1. Install GenemuFormBundle
2. Enable the bundle
3. Minimal configuration
4. Initialize assets

### Step 1: Install GenemuFormBundle

Check your Symfony2 version.

[Symfony2 branch v2.0.*](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/instalation/2.0.md)

[Symfony2 branch master](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/instalation/master.md)

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

### Step 3: Minimal configuration

``` yaml
# app/config/config.yml

genemu_form: ~
```

### Step 4: Initialize assets

``` bash
$ php app/console assets:install web/
```

## Template

You use GenemuFormBundle and you seen that it does not work!
Maybe you have forgotten form_javascript or form_stylesheet.

The principle is to separate the javascript, stylesheet and html.
This allows better integration of web pages.

[View a template example form view](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/template.md)

## FormType

### Captcha GD

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/index.md)

### ReCaptcha ([Google librairie](http://www.google.com/recaptcha)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/recaptcha/index.md)

### Tinymce ([download](http://www.tinymce.com/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/index.md)

### JQueryUi ([download](http://jqueryui.com/)):

- [Datepicker](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/datepicker/index.md)
- [Slider](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/slider/index.md)
- [Autocomplete](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/index.md)

### File ([uploadify library](http://www.uploadify.com)):

You can use [jcrop](http://deepliquid.com/content/Jcrop.html) to uploadify.
You send the image and crop or apply filter.

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/index.md)

### Image ([view demo](http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/image/index.md)

### Colorpicker ([view demo](http://www.eyecon.ro/colorpicker/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/color/index.md)

### Rating ([view demo](http://orkans-tmp.22web.net/star_rating/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/rating/index.md)

### Chosen ([view demo](http://harvesthq.github.com/chosen/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/index.md)

### Plain

A Form type that just renders the field as a p tag. This is useful for forms where certain field need to be shown but not editable. 

## Note
There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
