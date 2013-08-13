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
            'address'  => array(
                'options'   => array('label' => 'Enter your address'),
            ),
            'map'       => false,
            'latitude'  => array(
                'enabled'   => false,
                'hidden'    => true,
                'options'   => array('label' => 'Latitude'),
            ),
            'longitude' => array(
                'enabled'   => false,
                'hidden'    => true,
                'options'   => array('label' => 'Longitude'),
            ),
            'locality'  => array(
                'enabled'   => false,
                'hidden'    => true,
                'options'   => array('label' => 'Locality'),
            ),
            'country'   => array(
                'enabled'   => false,
                'hidden'    => false,
                'options'   => array('label' => 'Country'),
            ),
        ))
}
```

*To see which jQuery libraries to include, see the [html code](https://github.com/sgruhier/jquery-addresspicker/blob/master/demos/index.html) of the demo*
