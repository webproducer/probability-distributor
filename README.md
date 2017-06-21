# probability-distributor
PHP class that generates lists of items based on specified weights

## Example

```php
require_once 'vendor/autoload.php';

$d = new WP\ProbabilityDistributor([
    ['first', 40],
    ['second', 20],
    ['third', 35],
    ['fourth', 5]
]);
$totalCnt = 100000;
$res = [];
foreach ($d->generate($totalCnt) as $val) {
    $val = $d->next();
    if (!isset($res[$val])) {
        $res[$val] = 0;
    }
    $res[$val]++;
}
foreach ($res as $val => $valCnt) {
    printf("%s: %d (%.2f %%)\n", $val, $cnt, ($valCnt/$totalCnt)*100);
}
```
