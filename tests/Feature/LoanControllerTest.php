<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\LoanController;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_throw_exception_when_user_giver_is_invalid()
    {
        $userGiver = User::factory()->create(['password'=> bcrypt('password')]);
        $userReceiver = User::factory()->create(['email'=>'davidaer8847@gmail.com',   'password'=> bcrypt('password')]);

        $productSerial = ProductSerial::factory()->create();
        $product = Product::factory()->create();

        $payload = [
            'giver_email' => $userGiver->email,
            'giver_password' => 'invalid_password',
            'user_receiver_email' => $userReceiver->email,
            'user_receiver_password' => 'password',
            'products' => [
                [
                    'product_id' => $product->id,
                    'serial_id' => $productSerial->id,
                    'magazines' => 1,
                    'ammunition' => 10,
                ],
            ],
        ];

        Log::shouldReceive('error')->with('Erro ao realizar a transação: Unauthorized')->once();
        $loanController = app(LoanController::class);

        $response = $loanController->store(new Request($payload));

        $this->assertEquals('Unauthorized', $response->original['message']);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_should_throw_exception_when_user_reciver_is_invalid()
    {
        $userGiver = User::factory()->create(['password'=> bcrypt('password')]);
        $userReceiver = User::factory()->create(['email'=>'davidaer8847@gmail.com',   'password'=> bcrypt('password')]);

        $productSerial = ProductSerial::factory()->create();
        $product = Product::factory()->create();

        $payload = [
            'giver_email' => $userGiver->email,
            'giver_password' => 'password',
            'user_receiver_email' => $userReceiver->email,
            'user_receiver_password' => 'invalid_password',
            'products' => [
                [
                    'product_id' => $product->id,
                    'serial_id' => $productSerial->id,
                    'magazines' => 1,
                    'ammunition' => 10,
                ],
            ],
        ];

        Log::shouldReceive('error')->with('Erro ao realizar a transação: Unauthorized')->once();
        $loanController = app(LoanController::class);

        $response = $loanController->store(new Request($payload));

        $this->assertEquals('Unauthorized', $response->original['message']);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_should_fail_when_item_already_loaned()
    {
        $userGiver = User::factory()->create(['password'=> bcrypt('password')]);
        $userReceiver = User::factory()->create(['email'=>'davidaer8847@gmail.com',   'password'=> bcrypt('password')]);

        $productSerial = ProductSerial::factory()->create();
        $product = Product::factory()->create();

        $loan = Loan::factory()->create();

        $loanProduct = LoanProduct::factory()->create(
            [
                'product_id' => $product->id,
                'product_serial_id' => $productSerial->id,
                'loan_id' => $loan->id
            ]
        );

        $payload = [
            'giver_email' => $userGiver->email,
            'giver_password' => 'password',
            'user_receiver_email' => $userReceiver->email,
            'user_receiver_password' => 'password',
            'products' => [
                [
                    'product_id' => $product->id,
                    'serial_id' => $productSerial->id,
                    'magazines' => 1,
                    'ammunition' => 10,
                ],
            ],
        ];

        Log::shouldReceive('error')->never();

        $loanController = app(LoanController::class);

        $response = $loanController->store(new Request($payload));

        $this->assertEquals('Item already loaned out', $response->original['message']);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_store_creates_loan_successfully()
    {
        $userGiver = User::factory()->create(['password'=> bcrypt('password')]);
        $userReceiver = User::factory()->create(['email'=>'davidaer8847@gmail.com',   'password'=> bcrypt('password')]);

        $productSerial = ProductSerial::factory()->create();
        $product = Product::factory()->create();

        $payload = [
            'giver_email' => $userGiver->email,
            'giver_password' => 'password',
            'user_receiver_email' => $userReceiver->email,
            'user_receiver_password' => 'password',
            'products' => [
                [
                    'product_id' => $product->id,
                    'serial_id' => $productSerial->id,
                    'magazines' => 1,
                    'ammunition' => 10,
                ],
            ],
        ];

        $loanController = app(LoanController::class);


        $response = $loanController->store(new Request($payload));

        /** @var Loan  $loan */
        $loan = $response->original;

        $this->assertEquals($userReceiver->id, $loan->user_receipt_id);
    }
}
