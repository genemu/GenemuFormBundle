# Captcha GD Field

![Default captcha](https://github.com/genemu/GenemuFormBundle/raw/master/Resources/doc/captcha_gd/images/default.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    captcha: ~
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
    {{ block("form_widget_simple") }}
{% endblock %}

{% block genemu_captcha_javascript %}
    <script type="text/javascript">
        $(function () {
            {# Image will be refreshed when the link is clicked #}
            $('#{{ id }}_refresh').click(function() {
                $('#{{ id }}_image').attr('src', '{{ path('genemu_captcha_refresh') }}?' + Math.random());
            });
        });
    </script>

    {{ parent() }}
{% endblock %}

```

## Fixing Bug on IE6 and IE7

* add in your routing.yml

``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Resources/config/routing/base64.xml"
```

* also your captcha should be small, because IE supports only 2083 characters in requests (otherwise they are just skipped).
Working example: 100x30, grayscale, gif

## Hardcoding the captcha value (for testing)

You can define a static code for your test environment :

``` yml
# app/config/config_test.yml
genemu_form:
    captcha:
        code: 1234
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('captcha', 'genemu_captcha');

        // If you are using form for adding/editing entity (for example with FOSUserBundle user registration form)
        // you may need to mark field as "not a property" by using code

        // ->add('captcha', 'genemu_captcha',array("property_path" => false,));
}
```

## Extra:

[Default configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/default.md)
