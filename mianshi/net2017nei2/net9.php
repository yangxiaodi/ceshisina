<?php
/**
 * Fibonacci数列
Fibonacci数列是这样定义的：
F[0] = 0
F[1] = 1
for each i ≥ 2: F[i] = F[i-1] + F[i-2]
因此，Fibonacci数列就形如：0, 1, 1, 2, 3, 5, 8, 13, ...，在Fibonacci数列中的数我们称为Fibonacci数。给你一个N，你想让其变为一个Fibonacci数，每一步你可以把当前数字X变为X-1或者X+1，现在给你一个数N求最少需要多少步可以变为Fibonacci数。
输入描述:
输入为一个正整数N(1 ≤ N ≤ 1,000,000)


输出描述:
输出一个最小的步数变为Fibonacci数"

输入例子:
15

输出例子:
2
 */

function deal($n) {
    $arr = arr();
    for ($i = 1, $len = count($arr); $i < $len; $i++) {
        if ($n >= $arr[$i - 1] && $n <= $arr[$i]) {
            $a = $n - $arr[$i - 1];
            $b = $arr[$i] - $n;
            $c = $a < $b ? $a : $b;
            echo $c;
        }
    }
}
function arr() {
    $arr[0] = 0;
    $arr[1] = 1;
    for ($i = 2;; $i++) {
        $arr[$i] = $arr[$i - 1] + $arr[$i - 2];
        if ($arr[$i] > 1000000) {
            break;
        }
    }
    return $arr;
}
deal(15);