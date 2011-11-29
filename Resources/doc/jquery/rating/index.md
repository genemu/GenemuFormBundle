# JQueryRating Field ([view demo](http://orkans-tmp.22web.net/star_rating/))

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
            'oneVoteOnly' => true
        ))
}
```
