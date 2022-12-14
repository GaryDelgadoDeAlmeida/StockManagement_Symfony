<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OrderTotalAmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('order_total_amount', [$this, 'getOrderTotalAmount']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('orderTotalAmount', [$this, 'getOrderTotalAmount']),
        ];
    }

    public function getOrderTotalAmount($value)
    {
        return 0;
    }
}
