<?php
namespace Concrete\Package\CommunityStoreShippingExample\Src\CommunityStore\Shipping\Method\Types;

use Concrete\Core\Support\Facade\DatabaseORM as dbORM;
use Concrete\Package\CommunityStore\Src\CommunityStore\Cart\Cart as StoreCart;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;
use Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use Concrete\Package\CommunityStore\Src\CommunityStore\Shipping\Method\ShippingMethodTypeMethod;
use Concrete\Package\CommunityStore\Src\CommunityStore\Shipping\Method\ShippingMethodOffer as StoreShippingMethodOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="CommunityStoreExampleMethods")
 */
class ExampleShippingMethod extends ShippingMethodTypeMethod
{
    public function getShippingMethodTypeName() {
        return t('Example Method');
    }

    /**
     * @ORM\Column(type="float")
     */
    protected $rate;

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    public function addMethodTypeMethod($data)
    {
        return $this->addOrUpdate('add', $data);
    }

    public function update($data)
    {
        return $this->addOrUpdate('update', $data);
    }

    private function addOrUpdate($type, $data)
    {
        if ($type == "update") {
            $sm = $this;
        } else {
            $sm = new self();
        }
        // do any saves here
        $sm->setRate($data['rate']);
        $em = dbORM::entityManager();
        $em->persist($sm);
        $em->flush();
        return $sm;
    }

    public function dashboardForm($shippingMethod = null)
    {
        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
        $this->set('form', $app->make("helper/form"));
        $this->set('smt', $this);
        if (is_object($shippingMethod)) {
            $smtm = $shippingMethod->getShippingMethodTypeMethod();
        } else {
            $smtm = new self();
        }
        $this->set("smtm", $smtm);
    }

    public function isEligible()
    {
        $subtotal = StoreCalculator::getSubTotal();
        $totalWeight = StoreCart::getCartWeight();
        $customer = new StoreCustomer();

        // use information from the above (or elsewhere) to determine if shipping offer can be used
        return true;
    }

    public function getOffers() {
        $offers = array();

        // for each sub-rate, create a StoreShippingMethodOffer
        $offer = new StoreShippingMethodOffer();

        // then set the rate
        $offer->setRate($this->getRate());

        // then set a label for it
        $offer->setOfferLabel('First Offer');

        // add it to the array
        $offers[] = $offer;

        // continue adding further rates
        $offer = new StoreShippingMethodOffer();
        $offer->setRate($this->getRate() * 1.5);
        $offer->setOfferLabel('Second Offer');

        // further text details for the specific offer can be added via:
        $offer->setOfferDetails('Signature required on delivery');

        $offers[] = $offer;
        return $offers;
    }


}
