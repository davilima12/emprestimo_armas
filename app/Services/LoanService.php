<?php

namespace App\Services;

use App\Models\Loan;
use App\Repository\LoanRepository;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function listLoans()
    {
        return $this->loanRepository->getAll();
    }

    public function createLoan(array $data): Loan
    {
        return $this->loanRepository->create($data);
    }

    public function findLoan(int $id): Loan
    {
        return $this->loanRepository->findById($id);
    }

    public function updateLoan(int $id, array $data): bool
    {
        return $this->loanRepository->update($id, $data);
    }

    public function deleteLoan(int $id): bool
    {
        return $this->loanRepository->delete($id);
    }

    public function checkExistingLoan($products)
    {
        return $this->loanRepository->exists($products);
    }

    public function addLoanProducts(int $loanId, array $products)
    {
        return $this->loanRepository->addLoanProducts($loanId, $products);
    }
}
