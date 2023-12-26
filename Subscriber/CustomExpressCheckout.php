<?php

namespace KbEsdCheckerDeactivator\Subscriber;

use SwagPaymentPayPalUnified\Subscriber\ExpressCheckout as ExpressCheckout;

use Enlight\Event\SubscriberInterface;
use Enlight_Components_Session_Namespace as Session;
use Enlight_Controller_ActionEventArgs as ActionEventArgs;
use Enlight_View_Default as ViewEngine;
use Shopware\Models\Shop\Shop;
use SwagPaymentPayPalUnified\Components\ButtonLocaleService;
use SwagPaymentPayPalUnified\Components\DependencyProvider;
use SwagPaymentPayPalUnified\Components\PaymentMethodProviderInterface;
use SwagPaymentPayPalUnified\Components\Services\RiskManagement\EsdProductCheckerInterface;
use SwagPaymentPayPalUnified\Models\Settings\ExpressCheckout as ExpressSettingsModel;
use SwagPaymentPayPalUnified\Models\Settings\General as GeneralSettingsModel;
use SwagPaymentPayPalUnified\PayPalBundle\Components\SettingsServiceInterface;
use SwagPaymentPayPalUnified\PayPalBundle\Components\SettingsTable;
use UnexpectedValueException;


class CustomExpressCheckout extends ExpressCheckout implements SubscriberInterface
{
    /**
     * @return void
     */
    public function addExpressCheckoutButtonDetail(ActionEventArgs $args)
    {
        /*if ($args->getSubject()->View()->getAssign('sArticle')['esd'] === true) {
            return;
        }*/

        $swUnifiedActive = $this->paymentMethodProvider->getPaymentMethodActiveFlag(PaymentMethodProviderInterface::PAYPAL_UNIFIED_PAYMENT_METHOD_NAME);
        if (!$swUnifiedActive) {
            return;
        }

        /** @var GeneralSettingsModel|null $generalSettings */
        $generalSettings = $this->settingsService->getSettings();
        if (!$generalSettings || !$generalSettings->getActive()) {
            return;
        }

        /** @var ExpressSettingsModel|null $expressSettings */
        $expressSettings = $this->settingsService->getSettings(null, SettingsTable::EXPRESS_CHECKOUT);
        if (!$expressSettings || !$expressSettings->getDetailActive()) {
            return;
        }

        $view = $args->getSubject()->View();

        if (!$this->isUserLoggedIn()) {
            $view->assign('paypalUnifiedEcDetailActive', true);
            $this->addEcButtonBehaviour($view, $generalSettings);
            $this->addEcButtonStyleInfo($view, $expressSettings, $generalSettings);
        }
    }
}
