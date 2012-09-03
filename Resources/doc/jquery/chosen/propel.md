# Use JQueryChosen to Propel values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen_model', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\Member',
        ))
        ->add('cities', 'genemu_jquerychosen_model', array(
            'class' => 'Genemu\Bundle\ModelBundle\Model\City',
            'multiple' => true
        ));
}
```
