#FormBundle

[![Build Status](https://secure.travis-ci.org/genemu/GenemuFormBundle.png)](https://secure.travis-ci.org/genemu/GenemuFormBundle)

## Installation

Installation is quick and easy, 5 steps process

1. Download GenemuFormBundle
2. Configure the Autoloader
3. Enable the bundle
4. Minimal configuration
5. Initilize assets

### Step 1: Download GenemuFormBundle

Check your Symfony2 version.

[Symfony2 branch v2.*](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/instalation/2.0.md)

[Symfony2 branch master](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/instalation/master.md)

### Step 2: Configure the Autoloader

If it is the first Genemu bundle you install in your Symfony 2 project,
you need to add the Genemu namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Genemu' => __DIR__.'/../vendor/bundles',
));
```

### Step 3: Enable the bundle

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

### Step 4: Minimal configuration

``` yaml
# app/config/config.yml

genemu_form: ~
```

### Step 5: Initialize assets

``` bash
$ php app/console assets:install web/
```

## Template

[Create template form view](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/template.md)

## FormType

### Cpatcha GD

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/index.md)

### ReCaptcha ([Google librairie](http://www.google.com/recaptcha)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/recaptcha/index.md)

### Tinymce ([download](http://www.tinymce.com/)):

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/index.md)

### [JQueryUi librairie](http://jqueryui.com/):

- [Datepicker](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/datepicker/index.md)
- [Slider](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/slider/index.md)
- [Autocomplete](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/index.md)

### File ([uplodify librairie](http://www.uploadify.com)):

You can use [jcrop](http://deepliquid.com/content/Jcrop.html) to uploadify.
You send the image and crop or apply filter.

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/index.md)

### Image:

[View configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/image/index.md)

## Note

There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
