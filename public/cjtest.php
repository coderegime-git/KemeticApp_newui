
<?php
// Boot Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Services\CJDropshippingService;
class TestService extends CJDropshippingService {
    public function testGetProductList(array $filters = []): array
    {
        $params = array_merge(['pageNum' => 1, 'pageSize' => 20], $filters);
        $res    = $this->request('GET', '/product/list', $params);
        return $res ?? [];
    }
}
$service = new TestService();
foreach ([1, 100, 1000, 1001, 2000, 172826] as $page) {
    echo "--- Page $page ---\n";
    $res = $service->testGetProductList([
        'pageNum' => $page,
        'pageSize' => 8,
    ]);
    echo "Response code: " . ($res['code'] ?? 'null') . "\n";
    echo "Response message: " . ($res['message'] ?? 'null') . "\n";
    if (isset($res['data'])) {
        echo "Data total: " . ($res['data']['total'] ?? 'null') . "\n";
        echo "Data pageNum: " . ($res['data']['pageNum'] ?? 'null') . "\n";
        echo "Data list count: " . (isset($res['data']['list']) ? count($res['data']['list']) : 'null') . "\n";
    } else {
        echo "Data is null\n";
    }
}
