# Use JQueryAutocomplete to Choices Ajax values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('ajax_simple', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax'
            'ids' => array('id_1', 'id_2'),
        ))
        ->add('ajax_multiple', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax',
            'multiple' => true,
            'ids' => array('id_text', 'id_select'),
        ));
}
```

## Add function ajaxAction to Controller:

``` php
<?php
namespace MyNamespace;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyClassAjaxController extends Controller
{
    /**
     * @Route("/ajax", name="ajax")
     */
    public function ajaxAction(Request $request)
    {
        $value = $request->get('term');
        $id_text = $request->get('id_text'); // The value of text will be passed in the ajax request
        $id_select = $request->get('id_select'); // The value of select will be passed in the ajax request

        // .... (Search values)
        $search = array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        );

        $response = new Response();
        $response->setContent(json_encode($search));

        return $response;
    }
}
```
