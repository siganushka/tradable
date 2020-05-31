<?php

namespace App\Twig;

use App\Utils\CurrencyUtils;
use Siganushka\GenericBundle\Model\EnableInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $currencyUtils;

    public function __construct(CurrencyUtils $currencyUtils)
    {
        $this->currencyUtils = $currencyUtils;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('enable', [$this, 'enableFilter'], ['is_safe' => ['html']]),
            new TwigFilter('datetimeable', [$this, 'datetimeableFilter'], ['is_safe' => ['html']]),
            new TwigFilter('str_repeat', [$this, 'strRepeat'], ['is_safe' => ['html']]),
        ];
    }

    public function priceFilter(?int $amount, array $options = [])
    {
        return $this->currencyUtils->format($amount, $options);
    }

    public function enableFilter(EnableInterface $subject)
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
