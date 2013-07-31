# JQueryAddressPicker Field ([view demo](http://xilinus.com/jquery-addresspicker/demos/))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('geolocation', 'genemu_jquerygeolocation')
}
```

## With all options:
``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('geolocation', 'genemu_jquerygeolocation', array(
            'map'       => false,
            'latitude'  => array(
                'enabled'   => false,
                'hidden'    => true,
            ),
            'longitude' => array(
                'enabled'   => false,
                'hidden'    => true,
            ),
            'locality'  => array(
                'enabled'   => false,
                'hidden'    => true,
            ),
            'country'   => array(
                'enabled'   => false,
                'hidden'    => false,
            ),
        ))
}
```

*To see which jQuery libraries to include, see the [html code](https://github.com/sgruhier/jquery-addresspicker/blob/master/demos/index.html) of the demo*
