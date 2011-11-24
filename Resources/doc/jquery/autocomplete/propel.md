# Use JQueryAutocomplete to Propel values

## If not use doctrine:

``` yml
# app/config/config.yml
genemu_form:
    jqueryautocompleter:
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

[Ajax Propel](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/propel_ajax.md)
