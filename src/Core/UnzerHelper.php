<?php

/**
 * This file is part of OXID eSales Unzer module.
 *
 * OXID eSales Unzer module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Unzer module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Unzer module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright 2003-2021 OXID eSales AG
 * @link      http://www.oxid-esales.com
 * @author    OXID Solution Catalysts
 */

namespace OxidSolutionCatalysts\Unzer\Core;

use Exception;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DisplayError;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\Facts\Facts;
use OxidSolutionCatalysts\Unzer\PaymentExtensions\UnzerPayment;
use OxidSolutionCatalysts\Unzer\Model\Transaction;
use UnzerSDK\Exceptions\UnzerApiException;
use UnzerSDK\Resources\EmbeddedResources\BasketItem;
use UnzerSDK\Resources\Metadata;
use UnzerSDK\Resources\Payment;
use UnzerSDK\Resources\TransactionTypes\Charge;

class UnzerHelper
{
    /**
     * @param array|null|string $errorMsg
     */
    public static function addErrorToDisplay($errorMsg): void
    {
        // TODO Translate Errors
        $oDisplayError = oxNew(DisplayError::class);
        $oDisplayError->setMessage($errorMsg);
        Registry::getUtilsView()->addErrorToDisplay($oDisplayError);
    }

    /**
     * @param array|null|string $unzerErrorMessage
     *
     * @psalm-param 'order' $destination
     *
     * @return never
     */
    public static function redirectOnError(string $destination, $unzerErrorMessage = null)
    {
        self::addErrorToDisplay($unzerErrorMessage);
        // redirect to payment-selection page:
        $oSession = Registry::getSession();

        //Remove temporary order
        $oOrder = oxNew(Order::class);
        if ($oOrder->load($oSession->getVariable('sess_challenge'))) {
            $oOrder->delete();
        }

        $dstUrl = Registry::getConfig()->getShopCurrentUrl();
        $dstUrl .= '&cl=' . $destination;

        $dstUrl = $oSession->processUrl($dstUrl);
        Registry::getUtils()->redirect($dstUrl);
        exit;
    }

    /**
     * @param $destination
     * @return string
     */
    public static function redirecturl(string $destination, bool $blWithSessionId = false): string
    {
        // redirect to payment-selection page:
        $oSession = Registry::getSession();
        $dstUrl = Registry::getConfig()->getShopCurrentUrl();
        $destination = str_replace('?', '&', $destination);
        $dstUrl .= 'cl=' . $destination;

        if ($blWithSessionId) {
            $dstUrl .= '&force_sid=' . $oSession->getId();
        }

        return $oSession->processUrl($dstUrl);
    }
}
