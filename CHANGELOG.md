CHANGELOG
=========

### JQuery Chosen

    [BC BREAK] widget option cannot specify the choice type anymore :

    Before:

    ```
    $formBuilder
        ->add('country', 'genemu_jquerychosen', array(
            'widget' => 'country',
        ));
    ```

    After:

    ```
    $formBuilder->add('country', 'genemu_jquerychosen_country');
    ```