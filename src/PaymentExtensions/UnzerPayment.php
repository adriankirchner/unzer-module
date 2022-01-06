<?php

namespace OxidSolutionCatalysts\Unzer\PaymentExtensions;

use Exception;
use OxidEsales\Eshop\Application\Model\Payment as PaymentModel;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\Facts\Facts;
use OxidSolutionCatalysts\Unzer\Exception\Redirect;
use OxidSolutionCatalysts\Unzer\Service\Translator;
use OxidSolutionCatalysts\Unzer\Service\Unzer as UnzerService;
use UnzerSDK\Exceptions\UnzerApiException;
use UnzerSDK\Resources\Metadata;
use UnzerSDK\Resources\PaymentTypes\BasePaymentType;
use UnzerSDK\Resources\TransactionTypes\AbstractTransactionType;
use UnzerSDK\Unzer;

abstract class UnzerPayment
{
    public const CONTROLLER_URL = "order";
    public const RETURN_CONTROLLER_URL = "order";
    public const FAILURE_URL = "";
    public const PENDING_URL = "order&fnc=unzerExecuteAfterRedirect&uzrredirect=1";
    public const SUCCESS_URL = "thankyou";

    /** @var Session */
    protected $session;

    /** @var Unzer */
    protected $unzerSDK;

    /** @var Translator */
    protected $translator;

    /** @var UnzerService */
    protected $unzerService;

    /** @var string */
    protected $unzerOrderId;

    /** @var string */
    protected $paymentMethod;

    /** @var bool */
    protected $isRecurring = false;

    /** @var array */
    protected $allowedCurrencies = [];

    public function __construct(
        Session $session,
        Unzer $unzerSDK,
        Translator $translator,
        UnzerService $unzerService
    ) {
        $this->session = $session;
        $this->unzerSDK = $unzerSDK;
        $this->translator = $translator;
        $this->unzerService = $unzerService;

        $this->unzerOrderId = 'o' . str_replace(['0.', ' '], '', microtime(false));
    }

    public function getPaymentCurrencies(): array
    {
        return $this->allowedCurrencies;
    }

    public function isRecurringPaymentType(): bool
    {
        return $this->isRecurring;
    }

    public function getUnzerPaymentTypeObject(): BasePaymentType
    {
        throw new \Exception('Payment method not implemented yet');
    }

    /**
     * @throws UnzerApiException
     * @throws Exception
     */
    public function execute(): bool
    {
        $paymentType = $this->getUnzerPaymentTypeObject();

        $customer = $this->unzerService->getUnzerCustomer($this->session->getUser());
        $basket = $this->session->getBasket();

        $paymentProcedure = $this->unzerService->getPaymentProcedure($this->paymentMethod);

        $transaction = $paymentType->{$paymentProcedure}(
            $basket->getPrice()->getPrice(),
            $basket->getBasketCurrency()->name,
            $this->unzerService->prepareRedirectUrl(self::PENDING_URL, true),
            $customer,
            $this->unzerOrderId,
            $this->getMetadata()
        );

        $this->unzerService->setSessionVars($transaction);

        return true;
    }

    /**
     * @return Metadata
     * @throws Exception
     */
    public function getMetadata(): Metadata
    {
        $metadata = new Metadata();
        $metadata->setShopType("Oxid eShop " . (new Facts())->getEdition());
        $metadata->setShopVersion(ShopVersion::getVersion());
        $metadata->addMetadata('shopid', (string)Registry::getConfig()->getShopId());
        $metadata->addMetadata('paymentmethod', $this->paymentMethod);
        $metadata->addMetadata('paymentprocedure', $this->unzerService->getPaymentProcedure($this->paymentMethod));

        return $metadata;
    }
}
