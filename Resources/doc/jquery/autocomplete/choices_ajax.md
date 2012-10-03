# Use JQueryAutocomplete to Choices Ajax values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('ajax_simple', 'genemu_jqueryautocompleter_choice', array(
            'route_name' => 'ajax'
        ))
        ->add('ajax_multiple', 'genemu_jqueryautocompleter_choice', array(
            'route_name' => 'ajax',
            'multiple' => true
        ));
}
```

## Add function ajaxAction to Controller:

``` php
<?php
namespace MyNamespace;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
