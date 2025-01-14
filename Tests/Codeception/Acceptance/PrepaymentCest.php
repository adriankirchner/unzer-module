<?php

/**
* Copyright © OXID eSales AG. All rights reserved.
* See LICENSE file for license details.
*/

declare(strict_types=1);

namespace OxidSolutionCatalysts\Unzer\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidSolutionCatalysts\Unzer\Tests\Codeception\AcceptanceTester;

/**
 * @group unzer_module
 * @group SecondGroup
 */
final class PrepaymentCest extends BaseCest
{
    private $prePaymentLabel = "//label[@for='payment_oscunzer_prepayment']";

    protected function _getOXID(): array
    {
        return ['oscunzer_prpayment'];
    }

    /**
     * @param AcceptanceTester $I
     * @group PrepaymentTest
     */
    public function checkPaymentWorks(AcceptanceTester $I)
    {
        $I->wantToTest('Test Prepayment payment works');
        $this->_initializeTest();
        $orderPage = $this->_choosePayment($this->prePaymentLabel);
        $orderPage->submitOrder();

        $this->_checkSuccessfulPayment();
        $I->waitForText(rtrim(strip_tags(sprintf(
            Translator::translate('OSCUNZER_BANK_DETAILS_AMOUNT'),
            $this->_getPrice(),
            $this->_getCurrency()
        ))));
    }
}
