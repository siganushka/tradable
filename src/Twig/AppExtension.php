<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('enable', [$this, 'enableFilter'], ['is_safe' => ['html']]),
            new TwigFilter('datetimeable', [$this, 'datetimeableFilter'], ['is_safe' => ['html']]),
            new TwigFilter('str_repeat', [$this, 'strRepeat'], ['is_safe' => ['html']]),
        ];
    }

    public function priceFilter(int $amount, int $decimals = 2, string $decPoint = '.', string $thousandsSep = ',')
    {
        return number_format($amount / 100, $decimals, $decPoint, $thousandsSep);
    }

    public function enableFilter(\App\Model\EnableInterface $subject)
    {
        return $subject->isEnabled()
            ? '<span class="badge badge-pill badge-success">Y</span>'
            : '<span class="badge badge-pill badge-danger">N</span>';
    }

    public function datetimeableFilter(\DateTimeInterface $dateTime)
    {
        return sprintf('<span title="%s">%s</span>',
            $dateTime->format('Y/m/d H:i:s'),
            $dateTime->format('m/d H:i'));
    }

    public function strRepeat(string $input, int $multiplier)
    {
        return str_repeat($input, $multiplier);
    }
}
