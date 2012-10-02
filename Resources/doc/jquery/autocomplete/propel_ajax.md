# Use JQueryAutocomplete to Propel Ajax values

## If not use doctrine:

``` yml
# app/config/config.yml
genemu_form:
    autocompleter:
        doctrine: false
```

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter_model', array(
            'route_name' => 'ajax_member',
            'class' => 'Genemu\Bundle\ModelBundle\Model\Member',
        ))
        ->add('cities', 'genemu_jqueryautocompleter_model', array(
            'route_name' => 'ajax_city',
            'class' => 'Genemu\Bundle\ModelBundle\Model\City',
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

use Genemu\Bundle\ModelBundle\Model\MemberQuery;
use Genemu\Bundle\ModelBundle\Model\CityQuery;

class MyClassAjaxController extends Controller
{
    /**
     * @Route("/ajax_member", name="ajax_member")
     */
    public function ajaxMemberAction(Request $request)
    {
        $value = $request->get('term');

        $members = MemberQuery::create()->findByName($value.'%');

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

        $cities = CityQuery::create()->findByName($value.'%');

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
