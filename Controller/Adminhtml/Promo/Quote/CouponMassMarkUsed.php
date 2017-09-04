<?php
namespace Pmclain\TickCouponUsage\Controller\Adminhtml\Promo\Quote;

use Magento\SalesRule\Controller\Adminhtml\Promo\Quote;
use Magento\SalesRule\Model\RegistryConstants;

class CouponsMassMarkUsed extends Quote
{
    /** @var \Magento\SalesRule\Api\CouponRepositoryInterface */
    protected $_couponRepository;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilder */
    protected $_searchCriteriaBuilder;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\SalesRule\Api\CouponRepositoryInterface $couponRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter);
        $this->_couponRepository = $couponRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }
    public function execute()
    {
        $this->_initRule();
        $rule = $this->_coreRegistry->registry(RegistryConstants::CURRENT_SALES_RULE);

        if (!$rule->getId()) {
            $this->_forward('noroute');
        }

        $codesIds = $this->getRequest()->getParam('ids');

        if (is_array($codesIds)) {
            $searchCriteria = $this->_searchCriteriaBuilder->addFilter(
                'coupon_id',
                $codesIds,
                'in'
            )->create();

            $searchResult = $this->_couponRepository->getList($searchCriteria);

            foreach ($searchResult->getItems() as $coupon) {
                $coupon->setTimesUsed($coupon->getTimesUsed() + 1);
                $this->_couponRepository->save($coupon);
            }
        }
    }
}