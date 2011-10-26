FormBundle
==========

## Installation

### Step 1: Installation using the `bin/vendor` method

If you're using the `bin/vendors` method to manage your vendor libraries, add the following entries to the deps in the root of your project file:

    [GenemuFormBundle]
        git=http://github.com/genemu/GenemuFormBundle.git
        target=bundles/Genemu/Bundle/FormBundle

### Step 2: Add the namespace to your autoloader

If it is the first Genemu bundle you install in your Symfony 2 project, you
need to add the `Genemu` namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Genemu' => __DIR__.'/../vendor/bundles'
        // ...
    ));

### Step 3: Initialize the bundle

To start using the bundle, initialize the bundle in your Kernel. This
file is usually located at `app/AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
        );
    )

### Step 4: Minimal Configuration

Adds the following configuration to your `app/config/config.yml`:

    genemu_form: ~

## Add `form_javascript` and `form_stylesheet` to view

    {% block stylesheets %}
        <link href="{{ asset('css/ui-lightness/jquery-ui-1.8.16.custom.css') }}" rel="stylesheet" />

        <link href="{{ asset('css/uploadify/uploadify.css') }}" rel="stylesheet" />

        {{ form_stylesheet(form) }}
    {% endblock %}

    {% block javascripts %}
        <script src="{{ asset('js/jquery-1.6.4.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui-1.8.16.custom.min.js') }}"></script>
        <script src="{{ asset('tinymce/jquery.tinymce.js') }}"></script>

        <script src="{{ asset('js/uploadify/jquery.uploadify.v2.1.4.min.js') }}"></script>
        <script src="{{ asset('js/uploadify/swfobject.js') }}"></script>

        {{ form_javascript(form) }}
    {% endblock %}

    {% block body %}
        <form action="" type="post" {{ form_enctype(form) }}>
            {{ form_widget(form) }}

            <input type="submit" />
        </form>
    {% endblock %}

## Usage

### ReCaptcha (`http://www.google.com/recaptcha`)

Adds the following configuration to your `app/config/config.yml`:

    genemu_form:
        recaptcha:
            public_key:  `your public key is required`
            private_key: `your private key is required`
            options:
                theme:   clean
                use_ssl: false

The usage look like the field type. One example :

    $builder
        ->add('recaptcha', 'genemu_recaptcha');

### Captcha GD

Adds the following configuration to your `app/config/config.yml`:

    genemu_form:
        captcha:
            width:            100
            height:           40
            length:           4
            format:           png
            chars:            0123456789
            font_size:        18
            font_color:       ['252525', '8B8787', '550707', '3526E6', '88531E']
            font_dir:         %kernel.root_dir%/../web/bundles/genemuform/fonts
            fonts:            ['akbar.ttf', 'brushcut.ttf', 'molten.ttf', 'planetbe.ttf', 'whoobub.ttf']
            background_color: DDDDDD
            border_color:     000000

The usage look like the field type. One example :

    $builder
        ->add('captcha', 'genemu_captcha');

### Tinymce (`http://www.tinymce.com/`)

Adds the following configuration to your `app/config/config.yml`:

    genemu_form:
        tinymce:
            theme:       advanced
            script_url:  /tinymce/tiny_mce.js `(required)`
            configs:
                width:                           500px
                height:                          200px
                theme_advanced_toolbar_location: top
                // ...

The usage look like the field type. One example :

    $builder
        ->add('content', 'genemu_tinymce');

### JQueryDate (`http://jqueryui.com/demos/datepicker/`)

Adds the following configuration to your `app/config/config.yml`:

    genemu_form
        jquerydate:
            configs:
                buttonImage:     /images/date_button.png
                buttonImageOnly: true
                showAnim:        show
                // ....

The usage look like the field type. One example :

    $builder
        ->add('createdAt', 'genemu_jquerydate')
        ->add('updatedAt', 'genemu_jquerydate', array(
            'widget' => 'single_text'
        ));

### JQuerySlider (`http://jqueryui.com/demos/slider/`)

The usage look like the field type. One example :

    $builder
        ->add('price', 'genemu_jqueryslider', array(
            'min'         => 1,
            'max'         => 100,
            'step'        => 1,
            'orientation' => 'horizontal'
        ));

### JQueryAutocomplete (`http://jqueryui.com/demos/autocomplete/`)

The usage look like the field type. One example :

    $builder
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
            'class'  => 'Genemu\Bundle\EntityBundle\Entity\Member'
        ));

Create your autoloading ajax to `genemu_jqueryautocompleter` :
Add to FormType :

    $builder
        ->add('ajax', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_multiple',
            'multiple'   => true
        ))
        ->add('ajax2', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_simple'
        ))
        ->add('cities', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_city',
            'multiple'   => true,
            'widget'     => 'entity',
            'class'      => 'Genemu\Bundle\CityBundle\Entity\City'
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

        /**
         * @Route("/ajax_city", name="ajax_city")
         */
        public function ajaxEntityAction()
        {
            $request = $this->getRequest();
            $value = $request->get('term');

            $em = $this->getDoctrine()->getEntityManager();
            $cities = $em->getRepository('GenemuCityBundle:City')->findAjaxTerm($term);

            $array = array();
            foreach ($cities as $city) {
                $array[] = array(
                    'label' => $city->getName(),
                    'value' => $city->getId()
                );
            }

            $response = new Response();
            $response->setContent(json_encode($array));

            return $reponse;
        }
    }

### JQueryFile (`http://www.uploadify.com`)

Adds the following routing to your `app/config/routing.yml`:

    _genemu_upload:
        resource: "@GenemuFormBundle/Controller/UploadController.php"
        type:     annotation

Adds the following configuration to your `app/config/config.yml`:

    genemu_form:
        jqueryfile:
            uploader:   /uploadify/uploadify.swf
            cancel_img: /uploadify/cancel.png
            folder:     /uploads
            configs:
                auto:  true
                multi: true
                // ....

The usage look like the field type. One example :

    $builder
        ->add('file', 'genemu_jqueryfile');

## Note

There is maybe bugs in this implementations, this package is just an idea of a form
field type which can be very useful for the Symfony2 project.
