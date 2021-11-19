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

namespace OxidSolutionCatalysts\Unzer\Model\Payments;

class Przelewy24 extends UnzerPayment
{
    /**
     * @var string
     */
    protected string $Paymentmethod = 'przelewy24';

    /**
     * @return bool
     */
    public function isRecurringPaymentType(): bool
    {
        return false;
    }

    public function execute()
    {
        //TODO
    }
}
