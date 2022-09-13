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

namespace Ifthenpay\Payment\Block;

use Ifthenpay\Payment\Lib\Payments\Gateway;
use Ifthenpay\Payment\Helper\Factory\DataFactory;
use Ifthenpay\Payment\Lib\Traits\Payments\FormatReference;
use Magento\Framework\View\Element\Template\Context;

class Info extends \Magento\Payment\Block\Info
{
    use FormatReference;

    protected $dataFactory;

    public function __construct(Context $context, DataFactory $dataFactory,array $data = [])
    {
        parent::__construct($context, $data);
        $this->dataFactory = $dataFactory;
    }

    public function getSpecificInformation()
    {
        switch ($this->getMethodCode()) {
            case Gateway::MULTIBANCO:
                $informations[__('Entity')->render()] = $this->getInfo()->getAdditionalInformation('entidade');
                $informations[__('Reference')->render()] = $this->formatReference($this->getInfo()->getAdditionalInformation('referencia'));
                $validade = $this->getInfo()->getAdditionalInformation('validade');
                $requestId = $this->getInfo()->getAdditionalInformation('idPedido');
                if ($requestId) {
                    $informations[__('Request ID')->render()] = $requestId;
                }
                if ($validade) {
                    $informations[__('Deadline')->render()] = (new \DateTime($validade))->format('d-m-Y');
                }

                break;
            case Gateway::MBWAY:
                $informations[__('Request ID')->render()] = $this->getInfo()->getAdditionalInformation('idPedido');
                $informations[__('MB WAY Phone')->render()] = $this->getInfo()->getAdditionalInformation('telemovel');
                break;
            case Gateway::PAYSHOP:
                $informations[__('Request ID')->render()] = $this->getInfo()->getAdditionalInformation('idPedido');
                $informations[__('Reference')->render()] = $this->formatReference($this->getInfo()->getAdditionalInformation('referencia'));
                $informations[__('Deadline')->render()] = $this->getInfo()->getAdditionalInformation('validade') !== '' ? (new \DateTime($this->getInfo()->getAdditionalInformation('validade')))->format('d-m-Y') : '';

                break;
            case Gateway::CCARD:
                $informations[__('Request ID')->render()] = $this->getInfo()->getAdditionalInformation('idPedido');
                break;
            default:
                break;
        }
        $informations[__('Total to Pay')->render()] = $this->getInfo()->getAdditionalInformation('totalToPay') . $this->dataFactory->setType($this->getMethodCode())->build()->getCurrentCurrencySymbol();
        return (object)$informations;
    }

    public function getMethodCode()
    {
        return $this->getInfo()->getMethodInstance()->getCode();
    }
}
