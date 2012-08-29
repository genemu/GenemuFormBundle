# Use jQuery Select2 with Ajax

## Usage

Select2 requires a hidden input for ajax values, so you have to configure your form
this way :

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryselect2_hidden', array(
            'configs' => array(
                'multiple' => true // Wether or not multiple values are allowed (default to false)
            )
        ))
    ;
}
```

Then you'll have to customize the javascript template in your view :
```
{# ... #}

{% form_theme form _self %}

{% block genemu_jqueryselect2_javascript %}

    <script type="text/javascript">
        $field = $('#{{ id }}');

        var $configs = {{ configs|json_encode|raw }};

        // custom configs
        $configs = $.extend($configs, {
            query: function (query) {
                var data = {results: []}, i, j, s;
                for (i = 1; i < 5; i++) {
                    s = "";
                    for (j = 0; j < i; j++) {s = s + query.term;}
                    data.results.push({id: query.term + i, text: s});
                }
                query.callback(data);
            }
        });
        // end of custom configs

        $field.select2($configs);
    </script>

{% endblock %}
```

With the template from above, you'll be able to merge the configs passed through
your type options (under the configs key) with some custom ones, this should be used
for callback functions (as it would be a pain to write them in your form type itself).

For more information on all the available options, please cf. to the excellent
Select2 documentation : http://ivaynberg.github.com/select2/