# JQueryAutocomplete Field ([view demo](http://jqueryui.com/demos/autocomplete/))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('country', 'genemu_jquerychosen', array(
            'widget' => 'country'
        ))
        ->add('timezone', 'genemu_jquerychosen', array(
            'widget' => 'timezone'
        ))
        ->add('language', 'genemu_jquerychosen', array(
            'widget' => 'language'
        ));
}
```

## Add in your view, after calling the jQuery library
``` twig
<script src="{{ asset('bundles/genemuform/js/genemuFormBundle.js') }}"></script>
{{ form_javascript(form) }}
```

## Usage in Form Collection
When the chosen field is created within a collection, only the configuration is setup in the data-prototype.
In that case, you need to manually trigger the chosen function via a call to 

``` javascript
genemuFormBundleChosenEnable(chosenFieldId);
```

## Extra

1. [Choices](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/chosen/choices.md)
2. [Entity](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/chosen/entity.md)
3. [MongoDB](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/chosen/mongodb.md)
4. [Propel](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/chosen/propel.md)
