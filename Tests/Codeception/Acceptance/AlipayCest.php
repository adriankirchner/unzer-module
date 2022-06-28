<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSolutionCatalysts\Unzer\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use OxidSolutionCatalysts\Unzer\Tests\Codeception\AcceptanceTester;
use OxidSolutionCatalysts\Unzer\Tests\Codeception\Page\LocalPaymentMethodsSimulatorPage;

class AlipayCest extends BaseCest
{
    private $alipayPaymentLabel = "//label[@for='payment_oscunzer_alipay']";

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    private function _prepareAlipayTest(AcceptanceTester $I)
    {
        $I->updateInDatabase('oxpayments', ['OXACTIVE' => 1], ['OXID' => 'oscunzer_alipay']);
        $this->_setAcceptance($I);
        $this->_initializeTest();
        $orderPage = $this->_choosePayment($this->alipayPaymentLabel);
        $orderPage->submitOrder();
    }

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    private function _checkAlipayPayment(int $methodNumber)
    {
        $price = str_replace($this->_getPrice(), ',', '.');
        $alipayClientData = Fixtures::get('alipay_client');
        $alipayPage = new LocalPaymentMethodsSimulatorPage($this->_getAcceptance());

        $alipayPage->login($alipayClientData['username'], $alipayClientData['password'], $price);
        $alipayPage->choosePaymentMethod($methodNumber);
        $alipayPage->paymentSuccessful($price);

        $this->_getAcceptance()->waitForText($this->_getTranslator()->translate('THANK_YOU'));
    }

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    public function checkWalletBalancePaymentWorks(AcceptanceTester $I)
    {
        $I->wantToTest('Test Alipay Wallet Balance payment works');
        $this->_prepareAlipayTest($I);
        $this->_checkAlipayPayment(1);
    }

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    public function checkSomeLPMPaymentWorks(AcceptanceTester $I)
    {
        $I->wantToTest('Test Alipay Some LPM payment works');
        $this->_prepareAlipayTest($I);
        $this->_checkAlipayPayment(2);
    }

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    public function checkAnotherLPMPaymentWorks(AcceptanceTester $I)
    {
        $I->wantToTest('Test Alipay Another LPM Alipay payment works');
        $this->_prepareAlipayTest($I);
        $this->_checkAlipayPayment(3);
    }

    /**
     * @param AcceptanceTester $I
     * @group AlipayPaymentTest
     */
    public function checkOneMoreLPMPaymentWorks(AcceptanceTester $I)
    {
        $I->wantToTest('Test Alipay One more LPM Alipay payment works');
        $this->_prepareAlipayTest($I);
        $this->_checkAlipayPayment(4);
    }
}
