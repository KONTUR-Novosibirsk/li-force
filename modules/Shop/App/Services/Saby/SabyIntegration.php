<?php

namespace Modules\Shop\App\Services\Saby;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Accounts\App\Models\Account;
use Modules\Shop\App\Models\ShopCategory;
use Modules\Shop\App\Models\ShopOrder;
use Modules\Shop\App\Models\ShopProduct;
use Modules\Storage\App\Services\ImageService;

class SabyIntegration extends CurlService
{
    /**
     * Индификатор приложения
     */
    private string $appId;

    /**
     * Защищенный ключ
     */
    private string $secureKey;

    /**
     * Секретный ключ
     */
    private string $secretKey;

    /**
     * Токен авторизации
     */
    private string $token;

    /**
     * Сервис работы с изображениями
     */
    private ImageService $imageService;

    /**
     * @var int $companyId - уникальный номер компании
     */
    private int $companyId;

    public function __construct()
    {
        $this->appId = config('services.saby.SABY_APP_ID');
        $this->secureKey = config('services.saby.SABY_SECURE_KEY');
        $this->secretKey = config('services.saby.SABY_SECRET_KEY');
        $this->token = $this->getToken();
        $this->imageService = new ImageService();
    }

    /**
     * Запрос токена авторизации
     */
    private function getToken(): string
    {

        $url = 'https://online.sbis.ru/oauth/service/';

        $data = [
            'app_client_id' => $this->appId,
            'app_secret' => $this->secureKey,
            'secret_key' => $this->secretKey,
        ];

        $result = $this->queryPost($data, $url);

        return $result['token'];
    }

    /**
     * Получает список точек продаж
     */
    private function getPointSale(): array
    {
        $url = 'https://api.sbis.ru/retail/point/list?';

        $data = [
            'product' => 'retail',
            'withPhones' => 'false',
            'withPrices' => 'false',
            'withSchedule' => 'false',
            'page' => 0,
            'pageSize' => 500,
        ];

        return $this->queryGet($data, $url, $this->token);
    }

    public function getPointSaleArray(): array
    {
        return $this->getPointSale();
    }

    /**
     * Получает прайс-листы у текущей точки продаж
     */
    private function getPriceList(int $pointId): array
    {
        $url = 'https://api.sbis.ru/retail/nomenclature/price-list?';

        $data = [
            'pointId' => $pointId,
            'actualDate' => date('Y-m-d'),
            'page' => 0,
            'pageSize' => 1000,
        ];

        return $this->queryGet($data, $url, $this->token);
    }

    /**
     * Возвращает массив уникальных номеров прайс-листов
     *
     * @param array $priceLists
     *
     * @return array
     */
    private function getPriceListIds(array $priceLists): array
    {
        return array_column($priceLists['priceLists'], 'id');
    }

    /**
     * Получает компании
     *
     * @return array
     */
    private function getCompanyList(): array
    {
        $url = 'https://api.sbis.ru/retail/company/list';

        return $this->queryGet([], $url, $this->token);
    }

    /**
     * Возвращает массив уникальных номеров компаний
     *
     * @param array $companies
     *
     * @return array
     */
    private function getCompanyIds(array $companies): array
    {
        return array_column($companies['companies'], 'companyId');
    }

    /**
     * Получает склады по компаниям
     *
     * @param array $companiesIds
     *
     * @return array
     */
    private function getWarehouses(array $companiesIds): array
    {
        $url = 'https://api.sbis.ru//retail/company/warehouses?';
        $warehouses = [];

        foreach ($companiesIds as $companyId)
        {
            $warehouse = $this->queryGet(['companyId' => $companyId], $url, $this->token);
            $warehouses = array_merge($warehouses, $warehouse['warehouses']);
        }

        return $warehouses;
    }

    /**
     * Возвращает массив уникальных номеров складов
     *
     * @param array $warehouses
     *
     * @return array
     */
    private function getWarehousesIds(array $warehouses): array
    {
        return array_values(array_unique(array_column($warehouses, 'id')));
    }

    /**
     * Возвращает массив уникальных номеров продуктов
     *
     * @param array $products
     *
     * @return array
     */
    private function getProductsIds(array $products): array
    {
        return array_values(array_unique(array_column($products, 'id')));
    }

    /**
     * Получает остатки
     *
     * @param array $priceLists
     * @param array $products
     * @param bool $progress
     *
     * @return array
     */
    private function getBalances(array $priceLists, array $products, bool $progress = true): array
    {
        $companies = $this->getCompanyList();
        $companiesIds = $this->getCompanyIds($companies);

        $warehouses = $this->getWarehouses($companiesIds);
        $warehousesTds = $this->getWarehousesIds($warehouses);

        $productsIds = $this->getProductsIds($products);

        $balance = [];
        $cursor = 0;
        $step = 500;
        $processed = 0;
        $total = count($productsIds);

        /**
         * Получает остатки за несколько запросов
         */
        while (true) {
            $url = sprintf(
                'https://api.sbis.ru/retail/nomenclature/balances?nomenclatures=[%s]&priceListIds=[%s]&warehouses=[%s]&companies=[%s]',
                implode(',', array_slice($productsIds, $cursor, $step)),
                implode(',', $priceLists),
                implode(',', $warehousesTds),
                implode(',', $companiesIds)
            );

            $balance = array_merge($balance, $this->queryGet([], $url, $this->token)['balances']);
            $cursor += $step;

            $processed += $step;

            if ($progress) {
                Cache::put('catalog_update_progress', [
                    'status' => 'running',
                    'stage' => 'Получение остатков',
                    'progress' => (int)(($processed / $total) * 100),
                    'current' => $processed,
                    'total' => $total
                ], 600);
            }

            if (count($productsIds) < $cursor) break;
        }

        /**
         * Убирает значения равные или меньше 0 и дубликаты
         */
        $unique = [];

        foreach ($balance as $item) {
            if ($item['balance'] <= 0) {
                continue;
            }

            $unique[$item['nomenclature']] = $item;
        }

        return $unique;
    }

    /**
     * Запрос остатков для продуктов корзины
     *
     * @param int $pointId
     * @param array $cart
     *
     * @return array
     */
    public function getBalancesOrderProducts(int $pointId, array $cart): array
    {
        $productsIds = $cart['products']->pluck('id')->toArray();
        $priceLists = $this->getPriceList($pointId);
        $priceListsIds = $this->getPriceListIds($priceLists);
        $balance = $this->getBalances($priceListsIds, $productsIds, false);

        $errors = [];

        foreach ($cart['products'] as $product) {
            if (isset($balance[$product['saby_id']])) {
                if ($balance[$product['saby_id']]['balance'] < $product['count']) {
                    $errors[] = 'Продукта ' . $product['title'] . ' на остатке '
                        . $balance[$product['saby_id']]['balance'] . 'шт.';
                }
            } else $errors[] = 'Продукта ' . $product['title'] . ' нет на складе.';
        }

        return $errors;
    }

    /**
     * Получает каталог у текущей точки продаж
     *
     * @param int $pointId
     * @param int $position
     * @param  ?int  $priceList
     *
     * @return array
     */
    private function getProductList(int $pointId, int $position = 0, ?int $priceList = null): array
    {
        $url = 'https://api.sbis.ru/retail/v2/nomenclature/list?';

        $data = [
            'pointId' => $pointId,
            'noStopList' => true,
            'withBalance' => true,
            'withBarcode' => true,
            'searchString' => '',
            'pageSize' => 1000,
            'position' => $position,
            'order' => 'after',
            'product' => 'retail'
        ];

        if ($priceList) {
            $data['priceListId'] = $priceList;
        }

        return $this->queryGet($data, $url, $this->token);
    }

    /**
     * Построение дерева категорий
     */
    private function buildCategoryTree(array $items): array
    {
        $map = [];
        $tree = [];

        foreach ($items as $item) {
            $item['children'] = [];
            $map[$item['hierarchicalId']] = $item;
        }

        foreach ($map as &$node) {
            if (! empty($node['hierarchicalParent']) && isset($map[$node['hierarchicalParent']])) {
                $map[$node['hierarchicalParent']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }

    /**
     * Создание / обновление категорий в бд (рекурсия)
     */
    private function addCategories(array $treeCategories, int $level = 0, int $categoryId = 0): void
    {
        foreach ($treeCategories as $category) {
            $categoryDB = ShopCategory::query()->where('title', '=', $category['name'])->first();

            if (! $categoryDB) {
                $categoryDB = new ShopCategory();
                $categoryDB['saby_id'] = $category['id'];
                $categoryDB['title'] = $category['name'];
                $categoryDB['alias'] = Str::slug($category['name']);

                if ($categoryId) {
                    $categoryDB['parent_id'] = $categoryId;
                }

                $categoryDB->save();
            } else {
                if ($categoryDB['saby_id'] != $category['id']) {
                    $categoryDB['saby_id'] = $category['id'];

                    $categoryDB->save();
                }
            }

            if (! empty($category['children'])) {
                $this->addCategories($category['children'], $level + 1, $categoryDB['id']);
            }
        }
    }

    /**
     * Добавляет изображения в карточку текущего товара
     *
     * @throws Exception
     */
    private function addImages(array $images, int $productId): void
    {
        $url = 'https://api.sbis.ru/retail/';

        foreach ($images as $image) {
            $imageFile = $this->queryGet([], $url.$image, $this->token, true);
            $this->imageService->upload([
                'imageable_type' => ShopProduct::class,
                'imageable_id' => $productId,
                'group' => 'preview',
                'image' => $imageFile,
            ]);
        }
    }

    /**
     * Создание / обновление продуктов в бд
     *
     * @param array $products
     * @param array $balance
     *
     * @throws Exception
     */
    private function addProducts(array $products, array $balance): void
    {
        $processed = 0;
        $total = count($products);

        foreach ($products as $product) {
            $productDB = ShopProduct::query()->where('alias', '=', Str::slug($product['name']))->first();

            if ($productDB) {
                if ($productDB['price'] != $product['cost'] && $product['cost']) {
                    $productDB['price'] = $product['cost'];
                }
                if (isset($balance[$product['id']]) && $productDB['quantity'] != $balance[$product['id']]['balance']) {
                    $productDB['quantity'] = $balance[$product['id']]['balance'];
                }
                if ($productDB['point_saby'] != $product['point'] && $product['point']) {
                    $productDB['point_saby'] = $product['point'];
                }
                if ($productDB['price_list_saby'] != $product['priceList'] && $product['priceList']) {
                    $productDB['price_list_saby'] = $product['priceList'];
                }
                if ($productDB['saby_id'] != $product['id'] && $product['id']) {
                    $productDB['saby_id'] = $product['id'];
                }
                if ($productDB['external_id'] != $product['externalId'] && $product['externalId']) {
                    $productDB['external_id'] = $product['externalId'];
                }
//                if ($productDB['description'] != $product['description_simple'] && $product['description_simple']) {
//                    $productDB['description'] = $product['description_simple'];
//                }
            }
//            else {
//                $productDB = new ShopProduct();
//
//                if ($product['cost']) $productDB['price'] = $product['cost'];
//                if (isset($balance[$product['id']])) $productDB['quantity'] = $balance[$product['id']]['balance'];
//                if ($product['point']) $productDB['point_saby'] = $product['point'];
//                if ($product['priceList']) $productDB['price_list_saby'] = $product['priceList'];
//                if ($product['id']) $productDB['saby_id'] = $product['id'];
//                if ($product['externalId']) $productDB['external_id'] = $product['externalId'];
//            }

            $productDB?->save();

//            if ($product['images']) {
//                $this->addImages($product['images'], $productDB['id']);
//            }

            $processed++;

            Cache::put('catalog_update_progress', [
                'status' => 'running',
                'stage' => 'Обновление товаров',
                'progress' => (int)(($processed / $total) * 100),
                'current' => $processed,
                'total' => $total
            ], 600);
        }
    }

    /**
     * Получает каталог и записывает его в бд
     */
    public function getCatalog(): void
    {
        $this->setProgress('Получение точек продаж', 0);
        $points = $this->getPointSale();
        $this->setProgress('Получение точек продаж', 100);
        sleep(2);

        $categories = [];
        $products = [];
        $processed = 0;
        $totalCount = count($points['salesPoints']);
        $priceLists = [];

        $this->setProgress('Получение каталогов у точек продаж', 0);

        foreach ($points['salesPoints'] as $point) {
            $priceLists = array_merge($priceLists, $this->getPriceList($point['id']));

            $productList = [];
            $position = 0;

            while (true) {
                $queryList = $this->getProductList($point['id'], $position);
                $productList = array_merge($productList, $queryList['nomenclatures']);
                $position = !empty($queryList['nomenclatures'])
                    ? end($queryList['nomenclatures'])['hierarchicalId'] : -1;

                if (!$queryList['outcome']['hasMore']) break;
            }

            /**
             * Получает уникальные категории и продукты
             */
            foreach ($productList as $productSaby) {
                if ($productSaby['isParent']) {
                    $categories[$productSaby['name']] = $productSaby;
                } else {
                    $productSaby['point'] = $point['id'];
                    $productSaby['priceList'] = $priceLists['priceLists'][0]['id'];
                    $products[$productSaby['name']] = $productSaby;
                }
            }

            $processed++;

            $this->setProgress('Получение каталогов у точек продаж', $this->calcPercent($processed, $totalCount));
        }

        sleep(2);

//        for testing
//        $path = 'saby/products.json';
//        $json = Storage::get($path);
//        $products = json_decode($json, true);

//        $this->setProgress('Строится дерево категорий', 0);
//        $treeCategories = $this->buildCategoryTree($categories);
//        $this->setProgress('Строится дерево категорий', 100);
//        sleep(2);
//
//        $this->setProgress('Обновление категории', 0);
//        $this->addCategories($treeCategories);
//        $this->setProgress('Обновление категории', 100);
//        sleep(2);

        $balance = $this->getBalances($this->getPriceListIds($priceLists), $products);
        sleep(2);

        $this->addProducts($products, $balance);

        Cache::put('catalog_update_progress', [
            'status' => 'done',
            'stage' => 'Завершено',
            'progress' => 100,
            'current' => 100,
            'total' => 100
        ], 600);
    }

    /**
     * Сохраняет текущий прогресс обновления каталога в кэш.
     *
     * @param string $stage Название текущей стадии процесса.
     * @param int|float $progress Процент выполнения текущей стадии (0-100).
     * @param int $current Текущее значение прогресса (например, количество обработанных элементов).
     * @param int $total Общее количество элементов для обработки.
     *
     * @return void
     */
    private function setProgress(string $stage, int|float $progress, int $current = 0, int $total = 0): void
    {
        Cache::put('catalog_update_progress', [
            'status' => 'running',
            'stage' => $stage,
            'progress' => (int)$progress,
            'current' => $current,
            'total' => $total
        ], 600);
    }

    private function calcPercent($current, $total): int
    {
        if ($total === 0) return 0;
        return (int)(($current / $total) * 100);
    }

    /**
     * Json для физического лица
     *
     * @param Account $user
     *
     * @return array|null
     */
    private function dataNaturalPerson(Account $user): array|null
    {
        if ($user['counterparty']['data']['inn']['value'] || $user['counterparty']['data']['snils']['value'])
        return [
            "jsonrpc" => "2.0",
            "method" => "СБИС.ИнформацияОКонтрагенте",
            "params" => [
                "Участник" => [
                    "Email" => $user['email'],
                    "Телефон" => $user['phone'],
                    "СвФЛ" => [
                        "Фамилия" => $user['last_name'],
                        "Имя" => $user['full_name'],
                        "Отчество" => $user['counterparty']['data']['patronymic']['value'],
                        "ИНН" => $user['counterparty']['data']['inn']['value'],
                        "СНИЛС" => $user['counterparty']['data']['snils']['value'],
                        "ЧастноеЛицо" => "Да"
                    ]
                ]
            ],
            "id" => 1
        ];

        return null;
    }

    /**
     * Json для ИП
     *
     * @param Account $user
     *
     * @return array
     */
    private function dataIndividualEntrepreneur(Account $user): array
    {
        return [
            "jsonrpc" => "2.0",
            "method" => "СБИС.ИнформацияОКонтрагенте",
            "params" => [
                "Участник" => [
                    "Email" => $user['email'],
                    "Телефон" => $user['phone'],
                    "СвФЛ" => [
                        "Фамилия" => $user['last_name'],
                        "Имя" => $user['full_name'],
                        "Отчество" => $user['counterparty']['data']['patronymic']['value'],
                        "ИНН" => $user['counterparty']['data']['inn']['value'],
                        "ЧастноеЛицо" => "Нет"
                    ]
                ]
            ],
            "id" => 1
        ];
    }

    /**
     * Json для организации
     *
     * @param Account $user
     *
     * @return array
     */
    private function dataOrganization(Account $user): array
    {
        return [
            "jsonrpc" => "2.0",
            "method" => "СБИС.ИнформацияОКонтрагенте",
            "params" => [
                "Участник" => [
                    "Email" => $user['email'],
                    "Телефон" => $user['phone'],
                    "СвЮЛ" => [
                        "ИНН" => $user['counterparty']['data']['inn']['value'],
                        "КПП" => $user['counterparty']['data']['kpp']['value'],
                        "Название" => $user['counterparty']['data']['name']['value']
                    ]
                ]
            ],
            "id" => 1
        ];
    }

    /**
     * Получает или создает контрагента
     *
     * @param Account $user
     *
     * @return string|null
     */
    public function addCounterpartyInSaby(Account $user): string|null
    {
        $url = 'https://online.sbis.ru/service/?srv=1';

        $data = match ($user['counterparty']['type']) {
            'natural_person' => $this->dataNaturalPerson($user),
            'individual_entrepreneur' => $this->dataIndividualEntrepreneur($user),
            'organization' => $this->dataOrganization($user)
        };

        if ($data) $result = $this->queryPost($data, $url, $this->token);
        else return null;

        return $result['result']['Идентификатор'];
    }

    /**
     * Создает заказ в системе САБИ
     *
     * @param array $cartData
     * @param string $userName
     *
     * @return array
     */
    public function addOrderInSaby(array $cartData, string $userName): array
    {
//        $data = [
//            "jsonrpc" => "2.0",
//            "protocol" => 6,
//            "method" => "CRMLead.getCRMThemeByName",
//            "params" => [
//                "НаименованиеТемы" => "Тестим вызов"
//            ],
//            "id" => 1
//
//        ];
//
//        dd($this->queryPost($data, $url, $this->token));

//        $data = [
//                "jsonrpc" => "2.0",
//                "protocol" => 6,
//                "method" => "CRMClients.SaveCustomer",
//                "params" => [
//                    "CustomerData" => [
//                    "d" => [
//                        "Киркоров",
//                        "Вениамин",
//                        "Петрович",
//                        0,
//                        "Адрес физического лица",
//                        [
//                            "d" => [
//                                [
//                                    "mobile_phone",
//                                    "+73333333333"
//                                ],
//                                [
//                                    "email",
//                                    "search@search_client.com"
//                                ]
//                            ],
//                            "s" => [
//                                [
//                                    "t" => "Строка",
//                                    "n" => "Type"
//                                ],
//                                [
//                                    "t" => "Строка",
//                                    "n" => "Value"
//                                ]
//                            ],
//                            "_type" => "recordset",
//                            "f" => 1
//                        ]
//                    ],
//                    "s" => [
//                        [
//                            "n" => "Surname",
//                            "t" => "Строка"
//                        ],
//                        [
//                            "n" => "Name",
//                            "t" => "Строка"
//                        ],
//                        [
//                            "n" => "Patronymic",
//                            "t" => "Строка"
//                        ],
//                        [
//                            "n" => "Gender",
//                            "t" => "Число целое"
//                        ],
//                        [
//                            "n" => "Address",
//                            "t" => "Строка"
//                        ],
//                        [
//                            "t" => "Выборка",
//                            "n" => "ContactData"
//                        ]
//                    ],
//                        "_type" => "record",
//                        "f" => 0
//                    ]
//            ], "id" => 1
//        ];
//
//        dd($this->queryPost($data, $url, $this->token));

        $url = "https://online.sbis.ru/service/";
        $user = current_auth_user();
        if ($user) {
            $userName = $user['full_name'] . ' ' . $user['last_name'] . ' ';
            $userName .= $user['counterparty']['data']['patronymic']['value'];
        }

        $nomenclatures = [];

        foreach ($cartData['products'] as $product) {
            $nomenclatures[] = [
                "code" => (string) $product['saby_id'],
                "price" => (float) $product['price'],
                "count" => $product['count']
            ];
        }

        $data = [
            "jsonrpc" => "2.0",
            "method" => "CRMLead.insertRecord",
            "params" => [
                "Лид" => [
                    "d" => [
                        "Регламент" => 161304,
                        "Клиент" => [
                            "d" => [
                                //"Лицо" => 164884,
                                //"ИНН" => 500100732259,
                                "Наименование" => $userName,
                                "Type" => [2]
                            ],
                            "s" => [
                                //"Лицо" => "Строка",
                                //"ИНН" => "Строка",
                                "Наименование" => "Строка",
                                "Type" => "JSON-объект"
                            ]
                        ],
                        "Примечание" => "Заказ с сайта",
                        "UserConds" => [
                            "Номер формы, с которой пришли" => "123",
                            "Сумма в корзине" => (string) $cartData['totalPrice']
                        ],
                        "Nomenclatures" => $nomenclatures
                    ],

                    "s" => [
                        "Регламент" => "Число целое",
                        "Клиент" => "Запись",
                        "Примечание" => "Строка",
                        "UserConds" => "JSON-объект",
                        "Nomenclatures" => "JSON-объект"
                    ]
                ]
            ],
            "id" => 1
        ];

        return $this->queryPost($data, $url, $this->token);
    }

    /**
     * Проверка статуса сделки
     *
     * @param ShopOrder $order
     *
     * @return bool|string
     */
    public function checkOrderInSaby(ShopOrder $order): bool|string
    {
        $url = "https://online.sbis.ru/service/";

        $data = [
            "jsonrpc" => "2.0",
            "protocol" => 6,
            "method" => "CRMLead.getLeadStatus",
            "params" => [
                "ИдентификаторДокумента" => $order['saby_id']
            ],
            "id" => 1
        ];

        $result = $this->queryPost($data, $url, $this->token);

        return $result['result']['d'][3];
    }

    /**
     * Получает продукт со связями
     *
     * @param ShopProduct $product
     *
     * @return array|null
     */
    public function getProductAllCart(ShopProduct $product): array|null
    {
        if ($product['external_id']) {
            $url = "https://api.sbis.ru/retail/nomenclature/{$product['external_id']}?";

            $data = [
                'pointId' => $product['point_saby'],
                'page' => 0,
                'pageSize' => 50,
            ];

            return $this->queryGet($data, $url, $this->token);
        }

        return null;
    }
}
