<?php

namespace Woo\Marketplace\Models;

use Woo\Base\Models\BaseModel;
use Exception;
use Hash;
use MarketplaceHelper;

class VendorInfo extends BaseModel
{
    protected $table = 'mp_vendor_info';

    protected $fillable = [
        'customer_id',
        'balance',
        'total_fee',
        'signature',
        'total_revenue',
        'bank_info',
        'tax_info',
        'payout_payment_method',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'bank_info' => 'array',
        'tax_info' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (&$vendorInfo) {
            $model = new VendorInfo();
            $vendorInfo->balance = $vendorInfo->balance ?: 0;
            $vendorInfo->total_fee = $vendorInfo->total_fee ?: 0;
            $vendorInfo->total_revenue = $vendorInfo->total_revenue ?: 0;
            $vendorInfo->signature = Hash::make($model->getSignatureKey(false, $vendorInfo));

            return $vendorInfo;
        });

        static::updating(function (&$vendorInfo) {
            if ($vendorInfo->id) {
                $balanceOriginal = $vendorInfo->getOriginal('balance');
                $balance = $vendorInfo->balance;
                $totalFeeOriginal = $vendorInfo->getOriginal('total_fee');
                $totalFee = $vendorInfo->total_fee;
                $totalRevenueOriginal = $vendorInfo->getOriginal('total_revenue');
                $totalRevenue = $vendorInfo->total_revenue;
                if ($balanceOriginal != $balance || $totalFeeOriginal != $totalFee || $totalRevenueOriginal != $totalRevenue) {
                    if ($vendorInfo->isCheckSignature() && ! $vendorInfo->checkSignature()) {
                        throw new Exception(__('Invalid signature of vendor info'));
                    }
                    $vendorInfo->signature = Hash::make($vendorInfo->getSignatureKey(true));
                }
            }

            return $vendorInfo;
        });
    }

    public function isCheckSignature(): bool
    {
        return MarketplaceHelper::getSetting(
            'check_valid_signature',
            config('plugins.marketplace.general.check_signature_vendor', true)
        );
    }

    public function checkSignature(): string
    {
        return Hash::check($this->getSignatureKey(), $this->signature);
    }

    public function getSignatureKey(bool $isNew = false, $item = null): string
    {
        if (! $item) {
            $item = $this;
        }

        $balance = $isNew ? $item->balance : ($item->getOriginal('balance') ?: $item->balance);
        $totalFee = $isNew ? $item->total_fee : ($item->getOriginal('total_fee') ?: $item->total_fee);
        $totalRevenue = $isNew ? $item->total_revenue : ($item->getOriginal('total_revenue') ?: $item->total_revenue);

        return "$balance-$totalFee-$totalRevenue-{$item->customer_id}";
    }
}
