<?php

namespace App\Utils;

class CurrencyUtils
{
    private $options;

    public function __construct(array $defaults = [])
    {
        $this->options = array_merge([
            'divisor' => 100,
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ',',
        ], $defaults);
    }

    /**
     * 格式化货币，单位：分.
     *
     * @param int|null $amount 金额
     *
     * @return string 格式化后的金额
     */
    public function format(?int $amount, array $options = []): string
    {
        $scoped = array_merge($this->options, $options);

        return number_format(
            (null === $amount) ? 0 : ($amount / $scoped['divisor']),
            $scoped['decimals'],
            $scoped['dec_point'],
            $scoped['thousands_sep']);
    }
}
