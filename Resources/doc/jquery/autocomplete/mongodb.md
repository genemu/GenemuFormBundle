# Use JQueryAutocomplete to MongoDB values

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    autocompleter:
        mongodb: true
```

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\Member',
            'widget' => 'document'
        ))
        ->add('cities', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\City',
            'widget' => 'document',
            'multiple' => true
        ));
}
```

## Extra

[Ajax MongoDB](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/autocomplete/mongodb_ajax.md)
