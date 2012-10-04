# Use JQueryAutocomplete to MongoDB Ajax values

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    autocompleter:
        mongodb: true
```

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter_document', array(
            'route_name' => 'ajax_member',
            'class' => 'Genemu\Bundle\DocumentBundle\Document\Member',
        ))
        ->add('cities', 'genemu_jqueryautocompleter_document', array(
            'route_name' => 'ajax_city',
            'class' => 'Genemu\Bundle\DocumentBundle\Document\City',
            'multiple' => true
        ));
}
```

## Add functions to Controller:

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
     * @Route("/ajax_member", name="ajax_member")
     */
    public function ajaxMemberAction(Request $request)
    {
        $value = $request->get('term');

        $documentManager = $this->get('doctrine.odm.mongodb.document_manager');
        $members = $documentManager->getRepository('GenemuDocumentBundle:Member')->findAjaxValue($value);

        $json = array();
        foreach ($members as $member) {
            $json[] = array(
                'label' => $member->getName(),
                'value' => $member->getId()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

    /**
     * @Route("/ajax_city", name="ajax_city")
     */
    public function ajaxCityAction(Request $request)
    {
        $value = $request->get('term');

        $documentManager = $this->get('doctrine.odm.mongodb.document_manager');
        $cities = $documentManager->getRepository('GenemuDocumentBundle:City')->findAjaxValue($value);

        $json = array();
        foreach ($cities as $city) {
            $json[] = array(
                'label' => $member->getName(),
                'value' => $member->getId()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }
}
```
