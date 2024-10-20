<?php

namespace Tests\Unit\Commands;

use App\Constants\Periodicity;
use App\Constants\SuscriptionsStatus;
use App\Constants\TypesSites;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChargeSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_is_collected_successful(): void
    {
        $now = now();

        Carbon::setTestNow($now);

        $siteType = TypeSite::factory()->create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()
            ->for($siteType, 'typeSite')
            ->hasAttached(
                Currency::factory()
            )
            ->create();
        $plan = SuscriptionPlan::factory()->for($microsite)->create([
            'periodicity' => Periodicity::Monthly,
        ]);
        $payment = Payment::factory()->for($microsite)->create();
        $subscription = Suscription::factory()
            ->for($microsite)
            ->for($plan, 'suscriptionPlan')
            ->for($payment, 'initialPayment')
            ->create([
                'next_billing_date' => now()->subDay(),
                'expiration_date' => now()->addMonths(3),
                'status' => SuscriptionsStatus::ACTIVE,
            ]);

        Http::fake(fn(Request $request) => Http::response(json_encode($this->makeSuccessfulData())));

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 0,
            'status' => SuscriptionsStatus::ACTIVE->value,
        ]);

        $this->assertDatabaseCount('payments', 2);

        $this->travel(1)->month();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 0,
            'status' => SuscriptionsStatus::ACTIVE->value,
        ]);

        $this->assertDatabaseCount('payments', 3);

        $this->travel(1)->month();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 0,
            'status' => SuscriptionsStatus::ACTIVE->value,
        ]);

        $this->assertDatabaseCount('payments', 4);
    }

    public function test_subscription_is_suspended_when_collect_fails(): void
    {
        $now = now();

        Carbon::setTestNow($now);

        $siteType = TypeSite::factory()->create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()
            ->for($siteType, 'typeSite')
            ->hasAttached(
                Currency::factory()
            )
            ->create();
        $plan = SuscriptionPlan::factory()->for($microsite)->create();
        $payment = Payment::factory()->for($microsite)->create();
        $subscription = Suscription::factory()
            ->for($microsite)
            ->for($plan, 'suscriptionPlan')
            ->for($payment, 'initialPayment')
            ->create([
                'next_billing_date' => now()->subDay(),
                'expiration_date' => now()->addMonths(6),
                'status' => SuscriptionsStatus::ACTIVE,
            ]);

        Http::fake(fn(Request $request) => Http::response(json_encode($this->makeRejectedData())));

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 1,
            'status' => SuscriptionsStatus::FREEZE->value,
        ]);

        $this->travel(1)->day();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 2,
            'status' => SuscriptionsStatus::FREEZE->value,
        ]);

        $this->travel(1)->day();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 3,
            'status' => SuscriptionsStatus::SUSPENDED->value,
        ]);
    }

    public function test_subscription_is_collected_successful_with_suspension(): void
    {
        $now = now();
        $billingDate = now()->subDay();

        Carbon::setTestNow($now);

        $siteType = TypeSite::factory()->create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()
            ->for($siteType, 'typeSite')
            ->hasAttached(
                Currency::factory()
            )
            ->create();
        $plan = SuscriptionPlan::factory()->for($microsite)->create([
            'periodicity' => Periodicity::Monthly,
        ]);
        $payment = Payment::factory()->for($microsite)->create();
        $subscription = Suscription::factory()
            ->for($microsite)
            ->for($plan, 'suscriptionPlan')
            ->for($payment, 'initialPayment')
            ->create([
                'next_billing_date' => $billingDate,
                'expiration_date' => now()->addMonths(3),
                'status' => SuscriptionsStatus::ACTIVE,
            ]);

        Http::fakeSequence()
            ->push(json_encode($this->makeRejectedData()))
            ->push(json_encode($this->makeSuccessfulData()))
            ->push(json_encode($this->makeSuccessfulData()));

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 1,
            'status' => SuscriptionsStatus::FREEZE->value,
            'next_billing_date' => $billingDate->copy()->addDay()->startOfDay()->toDateTimeString(),
        ]);

        $this->assertDatabaseCount('payments', 2);

        $this->travel(1)->day();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 0,
            'status' => SuscriptionsStatus::ACTIVE->value,
            'next_billing_date' => $billingDate->copy()->addMonth()->startOfDay(),
        ]);

        $this->assertDatabaseCount('payments', 3);

        $this->travel(1)->month();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 0,
            'status' => SuscriptionsStatus::ACTIVE->value,
            'next_billing_date' => $billingDate->copy()->addMonths(2)->startOfDay(),
        ]);

        $this->assertDatabaseCount('payments', 4);
    }

    private function makeSuccessfulData(): array
    {
        return [
            'requestId' => fake()->randomNumber(5, true),
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2022-07-27T14:51:27-05:00'
            ],
            'request' => [
                'locale' => 'es_CO',
                'payer' => [
                    'document' => '1122334455',
                    'documentType' => 'CC',
                    'name' => 'John',
                    'surname' => 'Doe',
                    'company' => 'Evertec',
                    'email' => 'johndoe@app.com',
                    'mobile' => '+5731111111111',
                    'address' => [
                        'street' => 'Calle falsa 123',
                        'city' => 'Medellín',
                        'state' => 'Poblado',
                        'postalCode' => '55555',
                        'country' => 'Colombia',
                        'phone' => '+573111111111'
                    ]
                ],
                'buyer' => [
                    'document' => '1122334455',
                    'documentType' => 'CC',
                    'name' => 'John',
                    'surname' => 'Doe',
                    'company' => 'Evertec',
                    'email' => 'johndoe@app.com',
                    'mobile' => '+5731111111111',
                    'address' => [
                        'street' => 'Calle falsa 123',
                        'city' => 'Medellín',
                        'state' => 'Poblado',
                        'postalCode' => '55555',
                        'country' => 'Colombia',
                        'phone' => '+573111111111'
                    ]
                ],
                'payment' => [
                    'reference' => '12345',
                    'description' => 'Prueba de pago',
                    'amount' => [
                        'currency' => 'COP',
                        'total' => 2000,
                        'taxes' => [
                            [
                                'kind' => 'valueAddedTax',
                                'amount' => 1000,
                                'base' => 0
                            ]
                        ],
                        'details' => [
                            [
                                'kind' => 'discount',
                                'amount' => 1000
                            ]
                        ]
                    ],
                    'allowPartial' => false,
                    'shipping' => [
                        'document' => '1122334455',
                        'documentType' => 'CC',
                        'name' => 'John',
                        'surname' => 'Doe',
                        'company' => 'Evertec',
                        'email' => 'johndoe@app.com',
                        'mobile' => '+5731111111111',
                        'address' => [
                            'street' => 'Calle falsa 123',
                            'city' => 'Medellín',
                            'state' => 'Poblado',
                            'postalCode' => '55555',
                            'country' => 'Colombia',
                            'phone' => '+573111111111'
                        ]
                    ],
                    'items' => [
                        [
                            'sku' => '12345',
                            'name' => 'product_1',
                            'category' => 'physical',
                            'qty' => '1',
                            'price' => 1000,
                            'tax' => 0
                        ]
                    ],
                    'fields' => [
                        [
                            'keyword' => 'test_field_value',
                            'value' => 'test_field',
                            'displayOn' => 'approved'
                        ]
                    ],
                    'recurring' => [
                        'periodicity' => 'D',
                        'interval' => '1',
                        'nextPayment' => '2019-08-24',
                        'maxPeriods' => 1,
                        'dueDate ' => '2019-09-24',
                        'notificationUrl ' => 'https://checkout.placetopay.com'
                    ],
                    'subscribe' => false,
                    'dispersion' => [
                        [
                            'agreement' => '1299',
                            'agreementType' => 'MERCHANT',
                            'amount' => [
                                'currency' => 'USD',
                                'total' => 200
                            ]
                        ]
                    ],
                    'modifiers' => [
                        [
                            'type' => 'FEDERAL_GOVERNMENT',
                            'code' => 17934,
                            'additional' => [
                                'invoice' => '123345'
                            ]
                        ]
                    ]
                ],
                'subscription' => [
                    'reference' => '12345',
                    'description' => 'Ejemplo de descripción',
                    'fields' => [
                        'keyword' => '1111',
                        'value' => 'lastDigits',
                        'displayOn' => 'none'
                    ]
                ],
                'fields' => [
                    [
                        'keyword' => 'processUrl',
                        'value' => 'https://checkout.redirection.test/session/1/a592098e22acc709ec7eb30fc0973060',
                        'displayOn' => 'none'
                    ]
                ],
                'paymentMethod' => 'visa',
                'expiration' => '2019-08-24T14:15:22Z',
                'returnUrl' => 'https://commerce.test/return',
                'cancelUrl' => 'https://commerce.test/cancel',
                'ipAddress' => '127.0.0.1',
                'userAgent' => 'PlacetoPay Sandbox',
                'skipResult' => false,
                'noBuyerFill' => false,
                'type' => 'checkin'
            ],
            'payment' => [
                [
                    'status' => [
                        'status' => 'APPROVED',
                        'reason' => '00',
                        'message' => 'La petición ha sido aprobada exitosamente',
                        'date' => '2022-07-27T14:51:27-05:00'
                    ],
                    'internalReference' => 12345,
                    'reference' => '12345',
                    'paymentMethod' => 'visa',
                    'paymentMethodName' => 'Visa',
                    'issuerName' => 'JPMORGAN CHASE BANK, N.A.',
                    'amount' => [
                        'from' => [
                            'currency ' => 'COP',
                            'total ' => 10000
                        ],
                        'to' => [
                            'currency ' => 'COP',
                            'total ' => 10000
                        ],
                        'factor' => 1
                    ],
                    'receipt' => '052617800175',
                    'franchise' => 'PS_VS',
                    'refunded' => false,
                    'authorization' => '965960',
                    'processorFields' => [
                        [
                            'keyword' => '1111',
                            'value' => 'lastDigits',
                            'displayOn' => 'none'
                        ]
                    ],
                    'dispersion' => null,
                    'agreement' => null,
                    'agreementType' => null,
                    'discount' => [
                        'base' => 3000,
                        'code' => '17934',
                        'type' => 'FRANCHISE',
                        'amount' => 1000
                    ],
                    'subscription' => null
                ]
            ],
            'subscription' => [
                'status' => [
                    'status' => 'OK',
                    'reason' => '00',
                    'message' => 'La petición ha sido aprobada exitosamente',
                    'date' => '2022-07-27T14:51:27-05:00'
                ],
                'type' => 'token',
                'instrument' => [
                    [
                        'keyword' => 'token',
                        'value' => 'a3bfc8e2afb9ac5583922eccd6d2061c1b0592b099f04e352a894f37ae51cf1a',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'subtoken',
                        'value' => '8740257204881111',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'franchise',
                        'value' => 'visa',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'franchiseName',
                        'value' => 'Visa',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'issuerName',
                        'value' => 'JPMORGAN CHASE BANK, N.A.',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'lastDigits',
                        'value' => '1111',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'validUntil',
                        'value' => '2029-12-31',
                        'displayOn' => 'none'
                    ],
                    [
                        'keyword' => 'installments',
                        'value' => null,
                        'displayOn' => 'none'
                    ]
                ]
            ]
        ];
    }

    private function makeRejectedData(): array
    {
        return [
            'requestId' => fake()->randomNumber(4, true),
            'status' => [
                'status' => 'REJECTED',
                'reason' => 'XN',
                'message' => 'Se ha rechazado la petición',
                'date' => '2024-10-19T14:38:16-05:00'
            ],
            'request' => [
                'locale' => 'es_CO',
                'payer' => [
                    'document' => '1010457586',
                    'documentType' => 'CC',
                    'name' => 'jhon doe',
                    'surname' => 'pruebas',
                    'email' => 'pruebacertificacionp2p@gmail.com',
                    'mobile' => '+573106672283'
                ],
                'payment' => [
                    'reference' => 'pay-EA128BB0C6',
                    'description' => 'Pago suscripción - 2024-10-19 00:00:00',
                    'amount' => [
                        'currency' => 'COP',
                        'total' => 100000
                    ],
                    'allowPartial' => false,
                    'subscribe' => false
                ],
                'returnUrl' => 'https://checkout-co.placetopay.dev/home',
                'ipAddress' => '190.121.143.138',
                'userAgent' => 'GuzzleHttp/7'
            ],
            'payment' => [
                [
                    'amount' => [
                        'to' => [
                            'total' => 22499.01,
                            'currency' => 'CLP'
                        ],
                        'from' => [
                            'total' => 100000,
                            'currency' => 'COP'
                        ],
                        'factor' => 0.22499
                    ],
                    'status' => [
                        'date' => '2024-10-19T14:38:15-05:00',
                        'reason' => '05',
                        'status' => 'REJECTED',
                        'message' => 'Rechazada'
                    ],
                    'receipt' => '670815370147',
                    'refunded' => false,
                    'franchise' => 'PS_VS',
                    'reference' => 'pay-EA128BB0C6',
                    'issuerName' => 'BANCO DE GUAYAQUIL, S.A.',
                    'authorization' => '000000',
                    'paymentMethod' => 'visa',
                    'processorFields' => [
                        [
                            'value' => '4549106521651',
                            'keyword' => 'merchantCode',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => '98765432',
                            'keyword' => 'terminalNumber',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => 'C',
                            'keyword' => 'cardType',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => '411076',
                            'keyword' => 'bin',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => 1,
                            'keyword' => 'installments',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => true,
                            'keyword' => 'onTest',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => '0016',
                            'keyword' => 'lastDigits',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => '8fcc3f3bf5074f1222cd6a41b49bf644',
                            'keyword' => 'id',
                            'displayOn' => 'none'
                        ],
                        [
                            'value' => '05',
                            'keyword' => 'b24',
                            'displayOn' => 'none'
                        ]
                    ],
                    'internalReference' => 437297,
                    'paymentMethodName' => 'Visa'
                ]
            ],
            'subscription' => null
        ];
    }
}
