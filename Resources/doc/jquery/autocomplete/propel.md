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
        ->add('member', 'genemu_jqueryautocompleter_model', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\Member',
        ))
        ->add('cities', 'genemu_jqueryautocompleter_model', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\City',
            'multiple' => true
        ));
}
```

## Extra

[Ajax Propel](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/propel_ajax.md)
