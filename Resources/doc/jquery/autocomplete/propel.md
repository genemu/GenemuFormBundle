# Use JQueryAutocomplete to Propel values

## If not use doctrine:

``` yml
# app/config/config.yml
genemu_form:
    autocompleter:
        doctrine: false
```

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\Member',
            'widget' => 'model'
        ))
        ->add('cities', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\City',
            'widget' => 'model',
            'multiple' => true
        ));
}
```

## Extra

[Ajax Propel](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/autocomplete/propel_ajax.md)
