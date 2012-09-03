# Use JQueryChosen to Choices values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('choices', 'genemu_jquerychosen_choice', array(
            'choices' => array(
                'foo' => 'Foo',
                'bar' => 'Bar'
            )
        ));
}
```
