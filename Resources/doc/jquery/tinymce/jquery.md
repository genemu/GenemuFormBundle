# Tinymce Field ([download tinymce](http://www.tinymce.com/))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    tinymce:
        script_url: '/tinymce/tiny_mce.js'
```

Add your template:

``` twig
{% block javascripts %}
    <script src="{{ asset('js/jquery-1.7.min.js') }}"></script>
    <script src="{{ asset('tinymce/jquery.tinymce.js') }}"></script>

    {{ form_javascript(form) }}
{% endblock %}

{% block body %}
    <form action="{{ path('my_route_form') }}" type="post" {{ form_enctype(form) }}>
        {{ form_widget(form) }}

        <input type="submit" />
    </form>
{% endblock %}
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('content', 'genemu_tinymce');
}
```

## Extra:

[Configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/default.md)
