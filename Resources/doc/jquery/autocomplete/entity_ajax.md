# Use JQueryAutocomplete to Entity Ajax values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_member',
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
            'widget' => 'entity'
        ))
        ->add('cities', 'genemu_jqueryautocompleter', array(
            'route_name' => 'ajax_city',
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'widget' => 'entity',
            'multiple' => true,
            'ids' => array('member'),
        ));
}
```

## Add functions to Controller:

``` php
<?php
namespace MyNamespace;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $em = $this->getDoctrine()->getEntityManager();
        $members = $em->getRepository('GenemuEntityBundle:Member')->findAjaxValue($value);

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
        $member = $request->get('member'); // The value of member will be passed in the ajax request

        $em = $this->getDoctrine()->getEntityManager();
        $cities = $em->getRepository('GenemuEntityBundle:City')->findAjaxValue($value);

        $json = array();
        foreach ($cities as $city) {
            $json[] = array(
                'label' => $city->getName(),
                'value' => $city->getId()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }
}
```
