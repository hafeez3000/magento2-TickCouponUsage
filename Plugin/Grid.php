<?php
namespace Pmclain\TickCouponUsage\Plugin;

use Magento\SalesRule\Block\Adminhtml\Promo\Quote\Edit\Tab\Coupons\Grid as SubjectGrid;

class Grid
{
    public function beforeGetMassactionBlockHtml(SubjectGrid $subject)
    {
        $subject->getMassactionBlock()->addItem(
            'mark-used',
            [
                'label' => __('Mark Used'),
                'url' => $subject->getUrl('sales_rule/*/couponsMassMarkUsed', ['_current' => true]),
                'confirm' => __('Are you sure you want to mark the selected coupon(s) as used?'),
                'complete' => 'refreshCouponCodesGrid'
            ]
        );

        return [];
    }
}