<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @copyright 2003-2021 OXID eSales AG
 * @author    OXID Solution Catalysts
 * @link      https://www.oxid-esales.com
 */

// TODO: ApplePay is not yet part of the SDK, so the payment will come later. As of November 16, 2021
// TODO: Not implemented yet

namespace OxidSolutionCatalysts\Unzer\PaymentExtensions;

use UnzerSDK\Resources\PaymentTypes\BasePaymentType;

class ApplePay extends UnzerPayment
{
    protected $paymentMethod = 'applepay';

    public function getUnzerPaymentTypeObject(): BasePaymentType
    {
        throw new \Exception('Payment method not implemented yet');
    }
}
