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
        ->add('member', 'genemu_jqueryautocompleter_document', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\Member',
        ))
        ->add('cities', 'genemu_jqueryautocompleter_document', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\City',
            'multiple' => true
        ));
}
```

## Extra

[Ajax MongoDB](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/mongodb_ajax.md)
