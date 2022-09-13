<?php
/**
* Ifthenpay_Payment module dependency
*
* @category    Gateway Payment
* @package     Ifthenpay_Payment
* @author      Ifthenpay
* @copyright   Ifthenpay (http://www.ifthenpay.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

namespace Ifthenpay\Payment\Controller\Frontend;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Ifthenpay\Payment\Logger\IfthenpayLogger;
use Magento\Framework\Controller\ResultFactory;

class CCardRedirectToProvider extends Action
{

    protected $resultFactory;
    protected $_checkoutSession;
    private $logger;

    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        Session $checkoutSession,
        IfthenpayLogger $logger
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {

            $order = $this->_checkoutSession->getLastRealOrder();
            $payment = $order->getPayment();
            $paymentUrl = $payment->getAdditionalInformation('paymentUrl');
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl($paymentUrl);
        } catch (\Throwable $th) {
            $this->logger->debug('Error redirecting to ccard provider', [
                'error' => $th,
                'errorMessage' => $th->getMessage(),
                'paymentUrl' => $paymentUrl,
                'order' => $order,
                'payment' => $payment
            ]);
            throw $th;
        }
    }
}
