<?php
// Run: php hello.php
function factorial(int $n): int {
    return $n <= 1 ? 1 : $n * factorial($n - 1);
}

foreach (range(1, 8) as $i) {
    printf("%d! = %d\n", $i, factorial($i));
}
