UPGRADE to 2.1
==============

### JQuery Chosen

Widget option cannot specify the choice type anymore, you have to append the widget in the type name instead :

Before :
``` php
$formBuilder
    ->add('country', 'genemu_jquerychosen', array(
        'widget' => 'country',
));
```
Now :
``` php
$formBuilder->add('country', 'genemu_jquerychosen_country');
```

### JQuery Autocompleter

Same instructions than Chosen.

### Routing

Routing is defined in xml files instead of annotations (to remove the depency with SensioFrameworkExtraBundle)

Before :
 ``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Controller/Base64Controller.php"
    type:     annotation
 ```
Now :
 ``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Resources/config/routing/base64.xml"
 ```

### Captcha

The 'position' option has been removed, if you had it set to 'right', you should add your own template for this type.