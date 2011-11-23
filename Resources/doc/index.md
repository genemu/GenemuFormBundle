# Getting Started With GenemuFormBundle

## Installation

Installation is quick and easy, 4 steps process

1. Download GenemuFormBundle
2. Configure the Autoloader
3. Enable the bundle
4. Minimal Configuration

### Step 1: Download GenemuFormBundle

The files should be downloaded to the `vendor/bundles/Genemu/Bundle/FormBundle` directory.
This can be done in several ways, depending on your preference. The first method is the standard Symfony2 method.

**Using the `bin/vendor` method**

Add the following entries to the deps in the root of your project file:

```
[GenemuFormBundle]
    git=git://github.com/genemu/GenemuFormBundle.git
    target=bundles/Genemu/Bundle/FormBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, the run the following:

``` bash
$ git submodule add git://github.com/genemu/GenemuFormBundle.git vendor/bundles/Genemu/Bundle/FormBundle
$ git submodule update --init
```

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

### Step 4: Minimal Configuration

``` yaml
# app/config/config.yml

genemu_form: ~
```

### Next Steps

Now that you have completed the basic installation and configuration of the
GenemuFormBundle, you are ready to learn about more advanced features and usages
of the bundle.

The following documents are available:

1. [Create Form](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/create_form.md)
2. [ReCaptcha](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/recaptcha/index.md)
3. [Captcha GD](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/index.md)
4. [Tinymce (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/index.md)
5. [Datepicker (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/datepicker/index.md)
6. [Slider (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/slider/index.md)
7. [Aucotomplete (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/index.md)
9. [File (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/index.md)
3. [Image (JQuery)](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/image/index.md)
