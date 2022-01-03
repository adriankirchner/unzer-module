<?php

namespace OxidSolutionCatalysts\Unzer\PaymentExtensions;

use Exception;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Field as FieldAlias;
use UnzerSDK\Exceptions\UnzerApiException;

class InvoiceSecured extends UnzerPayment
{
    protected $paymentMethod = 'invoice-secured';

    protected $allowedCurrencies = ['EUR'];

    /**
     * @return void
     * @throws UnzerApiException
     * @throws Exception
     */
    public function execute()
    {
        /** @var \UnzerSDK\Resources\PaymentTypes\InvoiceSecured $inv_secured */
        $inv_secured = $this->unzerSDK->createPaymentType(new \UnzerSDK\Resources\PaymentTypes\InvoiceSecured());

        $user = $this->session->getUser();
        if ($birthdate = Registry::getRequest()->getRequestParameter('birthdate')) {
            $user->oxuser__oxbirthdate = new Field($birthdate, FieldAlias::T_RAW);
        }

        $customer = $this->unzerService->getSessionCustomerData();
        $basket = $this->session->getBasket();

        $uzrBasket = $this->unzerService->getUnzerBasket($this->unzerOrderId, $basket);

        $transaction = $inv_secured->charge(
            $basket->getPrice()->getPrice(),
            $basket->getBasketCurrency()->name,
            $this->unzerService->prepareRedirectUrl(self::CONTROLLER_URL),
            $customer,
            $this->unzerOrderId,
            $this->getMetadata(),
            $uzrBasket
        );

        $this->setSessionVars($transaction);

        $user->save();
    }
}
