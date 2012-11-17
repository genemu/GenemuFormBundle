# Captcha GD Field

![Default captcha](https://github.com/genemu/GenemuFormBundle/raw/master/Resources/doc/captcha_gd/images/default.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    captcha:
        enabled: true
        configs:
            default: ~
```

## Multiple configurations

``` yml
# app/config/config.yml
genemu_form:
    captcha:
        enabled: true
        configs:
            registration_form:
                width:              100
                height:             40
            comment_form:
                width:              80
                height:             30
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('captcha', 'genemu_captcha', array(
            'config_name' => 'default'
        ));

        // If you are using form for adding/editing entity (for example with FOSUserBundle user registration form)
        // you may need to mark field as "not a property" by using code

        // ->add('captcha', 'genemu_captcha',array("property_path" => false,));
}
```

## Default configuration

``` yml
# app/config/config.yml
genemu_form:
    captcha:
        enabled: true
        configs:
            default:
                width:                           100
                height:                          40
                length:                          4
                format:                          'png'
                chars:                           '0123456789'
                font_size:                       18
                font_size_spreading_range:       [0, 3]
                font_color:                      ['252525', '8B8787', '550707', '3526E6', '88531E']
                fonts:                           ['akbar.ttf', 'brushcut.ttf', 'molten.ttf', 'planetbe.ttf', 'whoobub.ttf']
                chars_rotate_range:              [-25, 25]
                chars_position_spreading_range:  [-3, 3]
                chars_spacing:                   0
                background_color:                'DDDDDD'
                background_stripes_number:       15
                border_color:                    '000000'
                border_size                      1
                grayscale:                       false
```

## Fonts configuration

```yaml
fonts:
    - '@AcmeBundle/Resources/fonts/fontname.ttf'    #bundle notation
    - '%kernel.web_dir%/../web/fonts/fontname.ttf'  #full path
    - 'fontname.ttf'                                #same as @GenemuFormBundle/Resources/fonts/fontname.ttf
```

## Allowing users to refresh the captcha

In order to allow the captcha refresh, there is a route to call, but you have to use custom form templates, here is a ready-to-use example :

1) Add the route :
 ``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Resources/config/routing/base64.xml"
 ```

2) Customize the templates

``` jinja
{# ... #}

{% form_theme form _self %}

{% block genemu_captcha_widget %}
    <img id="{{ id }}_image" src="{{ src }}" width="{{ width }}" height="{{ height }}" title="{{ name|trans }}" />
    {# We're putting a link there #}
    <a id="{{ id }}_refresh">Refresh</a>
    {{ block("field_widget") }}
{% endblock %}

{% block genemu_captcha_javascript %}
    <script type="text/javascript">
        $(function () {
            {# Image will be refreshed when the link is clicked #}
            $('#{{ id }}_refresh').click(function() {
                $('#{{ id }}_image').attr('src', '{{ path('genemu_captcha_refresh', {'name': config_name}) }}?' + Math.random());
            });
        });
    </script>

    {{ parent() }}
{% endblock %}

```

## Fix Bug to IE6 and IE7

* add in your routing.yml

``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Resources/config/routing/base64.xml"
```

* also your captcha should be small, because IE supports only 2083 characters in requests (otherwise they are just skipped).
Working example: 100x30, grayscale, gif