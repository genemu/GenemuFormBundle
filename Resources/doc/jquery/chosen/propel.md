# Use JQueryChosen to Propel values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\Member',
            'widget' => 'model'
        ))
        ->add('cities', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\City',
            'widget' => 'model',
            'multiple' => true
        ));
}
```
