FormBundle
==========

## Installation

### Get the bundle

To install the bundle, place it in the `vendor/bundles/Genemu/Bundle` directory of your project
(so that it lives at `vendor/bundles/Genemu/Bundle/FormBundle`). You can do this by adding
the bundle as a submodule, cloning it, or simply downloading the source.

    git submodule add https://github.com/genemu/GenemuFormBundle.git vendor/bundles/Genemu/Bundle/FormBundle

### Add the namespace to your autoloader

If it is the first Genemu bundle you install in your Symfony 2 project, you
need to add the `Genemu` namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Genemu'                         => __DIR__.'/../vendor/bundles'
        // ...
    ));

### Initialize the bundle

To start using the bundle, initialize the bundle in your Kernel. This
file is usually located at `app/AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
        );
    )

Adds the following configuration to your `app/config/config.yml`:

    genemu_form:
        tinymce:
            script_url:  /tinymce/tiny_mce.js
            theme:       advanced
            width:       ~
            height:      ~
            config:      your perso config
        recaptcha:
            server_url:     http://api.recaptcha.net
            server_url_ssl: https://api-secure.recaptcha.net
            public_key:     your public key
            private_key:    your private key
            use_ssl:        false
            theme:          clean
        doublelist:
            associated_first:   true
            class:              double_list
            class_select:       double_list_select
            label_associated:   Associated
            label_unassociated: Unassociated
        jquerydate:
            image:  your url to image
            config: your perso config

## Usage

The usage look like the field type. One full example :

    $builder
        ->add('content', 'genemu_tinymce')
        ->add('recaptcha', 'genemu_recaptcha')
        ->add('date', 'genemu_jquerydate')
        ->add('list', 'genemu_doublelist', array('choices' => array(
            'foo' => 'foo',
            'bar' => 'bar'
        )));

Example Validator ReCaptcha : 

    // ...
    use Genemu\Bundle\FormBundle\Validator\Constrains as Genemu;

    class MyClass {
        // ...
        /**
         * @Genemu\Reaptcha
         */
        $recaptcha;
    }

## Note

There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
