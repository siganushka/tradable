<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Generator;

use App\Entity\Order;

class TimestampNumberGenerator implements OrderNumberGeneratorInterface
{
    /**
     * 生成订单号，单机下重复概率为百万分之一，为了保证最小长度和相对唯一，将符合以下特征
     *
     * 1、固定长度（16 位）
     * 2、纯数字
     * 3、有先后顺序（可排序）
     * 4、更直观的体现时间范围（可反向解开对应时间）
     * 5、非自增长（避免被采集或暴露业务量）
     *
     * 生成规律：
     *
     * 1-2 位：年份，比如 19 代表 2019 年
     * 3-5 位：一年中的第几天，比如 016 就是一年中的第 16 天，最大值为 365
     * 6-10 位：第一天中的秒数，从 0 开始，最大 86400
     * 11-16 位：当前系统时间的微秒数（百万分之一秒）
     *
     * @param Order $order 订单对象
     */
    public function generate(Order $order): string
    {
        $current = new \DateTime();
        $midnight = new \DateTime('midnight');

        $y = $current->format('y');

        $z = $current->format('z');
        $z = str_pad($z, 3, '0', STR_PAD_LEFT);

        $s = $current->getTimestamp() - $midnight->getTimestamp();
        $s = str_pad($s, 5, '0', STR_PAD_LEFT);

        $u = $current->format('u');

        return $y.$z.$s.$u;
    }
}
