UPGRADE to 2.2
==============

### JQuery Tokeninput

JQuery widget options have to be moved to a ```configs``` key :

Before :
``` php
$formBuilder
    ->add('country', 'genemu_jquerytokeninput_country', array(
        'tokenLimit' => 2,
        'theme' => 'facebook',
    ));
```
Now :
``` php
$formBuilder
    ->add('country', 'genemu_jquerytokeninput_country', array(
        'configs' => array(
            'tokenLimit' => 2,
            'theme' => 'facebook',
        )
    ));
```
