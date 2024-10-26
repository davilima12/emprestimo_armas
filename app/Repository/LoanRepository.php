<?php

namespace App\Repository;

use App\Models\Loan;
use App\Models\LoanProduct;

class LoanRepository
{
    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $loan = Loan::findOrFail($id);


        LoanProduct::where('loan_id', $id)->delete();

        $this->addLoanProducts($id, $data['products']);

        return $loan->update($data);
    }

    public function delete(int $id): bool
    {
        $loan = Loan::findOrFail($id);
        return $loan->delete();
    }

    public function getAll()
    {
        return Loan::with(['userGiver', 'userReceiver', 'loanedProducts.product', 'loanedProducts.productSerial'])->get();
    }

    public function findById(int $id): Loan
    {
        return Loan::with(['userGiver', 'userReceiver', 'loanedProducts.product', 'loanedProducts.productSerial'])->findOrFail($id);
    }

    public function exists(array $products)
    {
        foreach ($products as $product) {
            $query = LoanProduct::where('product_id', $product['product_id'])->where('returned', false);

            if (!empty($product['serial_id'])) {
                $query->where('product_serial_id', $product['serial_id']);
            }

            if ($query->exists()) {
                return true;
            }
        }

        return false;
    }


    public function addLoanProducts(int $loanId, array $products)
    {
        foreach ($products as $product) {
            LoanProduct::create([
                'loan_id'           => $loanId,
                'product_id'        => $product['product_id'],
                'product_serial_id' => $product['serial_id'] ?? null,
                'magazines'         => $product['magazines'] ?? 0,
                'ammunition'        => $product['ammunition'] ?? 0,
                'returned'          => 1
            ]);
        }
    }

}
