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
            theme:       advanced
            script_url:  /tinymce/tiny_mce.js
            configs:
                width:                           500px
                height:                          200px
                theme_advanced_toolbar_location: top
                // ...
        recaptcha:
            public_key:  your public key
            private_key: your private key
            options:
                theme:   clean
                use_ssl: false
        jquerydate:
            configs:
                buttonImage:     /images/date_button.png
                buttonImageOnly: true
                showAnim:        show
                // ....

## Usage

The usage look like the field type. One full example :

    $builder
        ->add('content', 'genemu_tinymce')
        ->add('recaptcha', 'genemu_recaptcha')
        ->add('date', 'genemu_jquerydate')
        ->add('date2', 'genemu_jquerydate', array(
            'widget' => 'single_text'
        ))
        ->add('country', 'genemu_jqueryautocompleter', array(
            'widget' => 'country'
        ))
        ->add('language', 'genemu_jqueryautocompleter', array(
            'widget' => 'language'
        ))
        ->add('choices', 'genemu_jqueryautocompleter', array(
            'choices' => array(
                'foo' => 'Foo',
                'bar' => 'Bar'
            ),
            'multiple' => true
        ))
        ->add('member', 'genemu_jqueryautocompleter', array(
            'widget' => 'entity'
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member'
        ));

## Note

There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
