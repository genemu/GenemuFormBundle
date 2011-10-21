FormBundle
==========

## Installation

### Installation using the `bin/vendor` method

If you're using the `bin/vendors` method to manage your vendor libraries, add the following entries to the deps in the root of your project file:

    [GenemuFormBundle]
        git=http://github.com/genemu/GenemuFormBundle.git
        target=bundles/Genemu/Bundle/FormBundle

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
        ))
        ->add('price', 'genemu_jqueryslider', array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
            'orientation' => 'horizontal'
        ));

## Add `form_javascript` to view

    {% block stylesheets %}
        <link href="{{ asset('css/ui-lightness/jquery-ui-1.8.16.custom.css') }}" rel="stylesheet" />
    {% endblock %}

    {% block javascripts %}
        <script src="{{ asset('js/jquery-1.6.4.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui-1.8.16.custom.min.js') }}"></script>
        <script src="{{ asset('tinymce/jquery.tinymce.js') }}"></script>

        {{ form_javascript(form) }}
    {% endblock %}

    {% block body %}
        <form action="" type="post" {{ form_enctype(form) }}>
            {{ form_widget(form) }}

            <input type="submit" />
        </form>
    {% endblock %}

## Create your autoloading ajax to `genemu_jqueryautocompleter`

Add to FormType :

    $builder
        ->add('ajax', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_multiple',
            'multiple' => true
        ))
        ->add('ajax2', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_simple'
        ));

Add to Controller :

    namespace MyNamespace;

    class MyClass extends Controller
    {
        /**
         * @Route("/ajax", name="ajax_multiple")
         */
        public function ajaxAction()
        {
            $request = $this->getRequest();
            $value = $request->get('term');

            // .... (Search values)

            $response = new Response();
            $response->setContent(json_encode(
                array(
                    array(
                        'label' => 'Foo',
                        'value' => 'foo'
                    ),
                    array(
                        'label' => 'Bar',
                        'value' => 'bar'
                    )
                )
            ));

            return $response;
        }

        /**
         * @Route("/ajax2", name="ajax_simple")
         */
        public function ajax2Action()
        {
            $request = $this->getRequest();
            $value = $request->get('term');

            // .... (Search values)

            $response = new Response();
            $response->setContent(json_encode(array('foo', 'bar')));

            return $reponse;
        }
    }

## Note

There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
