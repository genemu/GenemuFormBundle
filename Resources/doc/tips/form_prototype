# Prototype usage within form collections

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

    {{ form_widget(form.description) }}

    <div id="tags" data-prototype="{{ form_widget(form.tags.vars.prototype)|e }}">
        {{ form_widget(form.tags) }}
    </div>

</form>

<script type="text/javascript">

function triggerJavascript(id)
{
    $field = $('#' + id);

    {{ form_javascript(form.tags.vars.prototype, true) }}
}

function addTagForm(collectionHolder, $newLinkLi) {
/* Code from symfony cookbook
    // Get the data-prototype we explained earlier
    var prototype = collectionHolder.attr('data-prototype');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on the current collection's length.
    var id = collectionHolder.children().length;
    var newForm = prototype.replace(/__name__/g, id);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
*/

    // Once HTML has been added, let's trigger the javascript on it
    triggerJavascript('{{ form.tags.vars.id }}_' + id);
}

// ...
</script>
```