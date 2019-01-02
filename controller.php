<?php

namespace Concrete\Package\CommunityStoreShippingExample;

use Package;
use Whoops\Exception\ErrorException;
use Concrete\Package\CommunityStore\Src\CommunityStore\Shipping\Method\ShippingMethodType as StoreShippingMethodType;

class Controller extends Package
{
    protected $pkgHandle = 'community_store_shipping_example';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '2.0';

    public function getPackageDescription()
    {
        return t("An example of how to create a Shipping Method for Community Store");
    }

    public function getPackageName()
    {
        return t("Example Shipping Method Type");
    }

    public function install()
    {
        $installed = Package::getInstalledHandles();
        if(!(is_array($installed) && in_array('community_store',$installed)) ) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        } else {
            $pkg = parent::install();
            StoreShippingMethodType::add('example', 'Example Shipping', $pkg);
        }

    }
    public function uninstall()
    {
        $pm = StoreShippingMethodType::getByHandle('example');
        if ($pm) {
            $pm->delete();
        }
        $pkg = parent::uninstall();
    }

}
?>