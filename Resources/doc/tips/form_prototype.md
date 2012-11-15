# Prototype usage within form collections

WARNING : This is currently not available for all types, please open an issue or
a PR if it doesn't work for your type.

If you dynamically add one of our field in your template using the Symfony protoype feature,
you'd probably notify that it doesn't show up correctly. Indeed, most of our types require some javascript,
but you only added some HTML.

Here comes the javascript prototype option, if you use true as second argument of the twig
form_javascript function, it will only render the javascript main code, so
you'll need first to grab the element and put it in a jQuery var nammed 'field'.

Here is an example (cf. the symfony collection tutorial), let's say you add some
tags that are of select2 type :
```jinja
<form action="...>
<!-- Render the form -->
</form>

<script type="text/javascript">

function triggerJavascript(id)
{
    $field = $('#' + id);

    {{ form_javascript(form.tags.vars.prototype, true) }}
}

function addTagForm(collectionHolder, $newLinkLi)
{
    // Dynamically add the form and get its id
    var id = '{{ form.tags.vars.id }}_' + id;

    // Once HTML has been added, let's trigger the javascript on it
    triggerJavascript(id);
}

// ...
</script>
```