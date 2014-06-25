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
                'hidden'    => false,
            ),
            'longitude' => array(
                'enabled'   => false,
                'hidden'    => false,
            ),
            'locality'  => array(
                'enabled'   => false,
                'hidden'    => false,
            ),
            'country'   => array(
                'enabled'   => false,
                'hidden'    => false,
            ),
        ))
}
```

*To see which jQuery libraries to include, see the [html code](https://github.com/sgruhier/jquery-addresspicker/blob/master/demos/index.html) of the demo*

## Underlying data

The mapped property (in this example "geolocation") will receive an ``AddressGeolocation`` object.
This object is serializable, for example you can map it to a Doctrine `object` field.

## Using map display

If you display the map, do not forget to enabled `latitude` and `logitude`.
Those fields are use to update the marker on the map, especially when you are on a form edit.
If you do not need to display those fields, set `hidden` to true.


