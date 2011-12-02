# JQueryRating Field ([view demo](http://orkans-tmp.22web.net/star_rating/))

![Star rating](https://github.com/genemu/GenemuFormBundle/raw/master/Resources/doc/jquery/rating/images/default.png)

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('rating', 'genemu_jqueryrating')
        ->add('single_rating', 'genemu_jqueryrating', array(
            'configs' => array(
                'oneVoteOnly' => true
            )
        ))
}
```
