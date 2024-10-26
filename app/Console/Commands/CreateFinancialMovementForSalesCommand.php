<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Features\Finance\Dtos\FinancialMovementDto;
use App\Features\Finance\Enum\MovementTypes;
use App\Features\Finance\Exceptions\InvalidFinancialMovementException;
use App\Features\Finance\Models\FinancialMovement;
use App\Features\Finance\Services\FinancialMovementService;
use App\Features\Product\Exceptions\InvalidAmountException;
use App\Features\Sales\Models\Sale;
use Illuminate\Console\Command;

class CreateFinancialMovementForSalesCommand extends Command
{
    protected $signature = 'financial-movement:create-for-sales';

    protected $description = 'Cria uma entrada na movimentação financeira para vendas que ainda não possuem entradas';

    public function handle(): void
    {
        Sale::all()
            ->filter($this->withoutFinancialMovement(...))
            ->each($this->createFinancialMovement(...));
    }

    /**
     * @throws InvalidFinancialMovementException
     * @throws InvalidAmountException
     */
    private function createFinancialMovement(Sale $sale): void
    {
        $product = $sale->saleProduct->product;
        $dto = new FinancialMovementDto(
            amount: $sale->getValueAsAmount(),
            movementType: MovementTypes::CASH_IN,
            description: "Comissão Venda #$sale->id: $product->marca / $product->modelo",
            isCompleted: true,
            qtyRepeat: 1,
            startDate: now(),
        );
        app(FinancialMovementService::class)->create($dto);
    }

    private function withoutFinancialMovement(Sale $sale): bool
    {
        return FinancialMovement::query()
            ->where('description', 'like', "%Comissão Venda #$sale->id%")
            ->doesntExist();
    }
}
