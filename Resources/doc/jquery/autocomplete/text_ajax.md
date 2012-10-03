# Use JQueryAutocomplete to suggest values using Ajax

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('ajax_simple', 'genemu_jqueryautocomplete_text', array(
            'route_name' => 'ajax'
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
        $search = array('Bar', 'Foo');

        $response = new Response();
        $response->setContent(json_encode($search));

        return $response;
    }
}
```
