# Use JQueryChosen to MongoDB values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\Member',
            'widget' => 'document'
        ))
        ->add('cities', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\City',
            'widget' => 'document',
            'multiple' => true
        ));
}
```
