<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\LoanProduct;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index()
    {
        $loans = $this->loanService->listLoans();
        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_receiver_email' => 'required|email',
            'user_receiver_password' => 'required|string',
            'giver_email' => 'required|email',
            'giver_password' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => ['required', Rule::exists('products', 'id')],
            'products.*.serial_id' => ['nullable', Rule::exists('product_serials', 'id')],
            'products.*.magazines' => 'integer|min:0',
            'products.*.ammunition' => 'integer|min:0',
        ]);

        // Autenticação do usuário que está emprestando
        if (!Auth::attempt(['email' => $request->giver_email, 'password' => $request->giver_password])) {
            return response()->json(['message' => 'Unauthorized - Giver'], 401);
        }

        $userGiverId = Auth::id();

        // Autenticação do usuário que está recebendo
        if (!Auth::attempt(['email' => $request->user_receiver_email, 'password' => $request->user_receiver_password])) {
            return response()->json(['message' => 'Unauthorized - Receiver'], 401);
        }

        $userReceiverId = User::where('email', $request->user_receiver_email)->first()->id;

        $existingLoan = $this->loanService->checkExistingLoan($request->products);

        if ($existingLoan) {
            return response()->json(['message' => 'Item already loaned out'], 400);
        }

        $loanData = [
            'user_giver_id' => $userGiverId,
            'user_receiver_id' => $userReceiverId,
            'product_serial_id' => $request->product_serial_id,
            'magazines' => $request->magazines,
            'ammunition' => $request->ammunition,
        ];

        $loan = $this->loanService->createLoan($loanData);

        $this->loanService->addLoanProducts($loan->id, $request->products);
        return response()->json($loan, 201);
    }

    public function show($id)
    {
        $loan = $this->loanService->findLoan($id);
        return response()->json($loan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_receiver_email' => 'required|email',
            'user_receiver_password' => 'required|string',
            'giver_email' => 'required|email',
            'giver_password' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => ['required', Rule::exists('products', 'id')],
            'products.*.serial_id' => ['nullable', Rule::exists('product_serials', 'id')],
            'products.*.magazines' => 'integer|min:0',
            'products.*.ammunition' => 'integer|min:0',
        ]);

        // Autenticação do usuário que está emprestando
         if (!Auth::attempt(['email' => $request->giver_email, 'password' => $request->giver_password])) {
            return response()->json(['message' => 'Unauthorized - Giver'], 401);
        }

        $userGiverId = Auth::id();

        // Autenticação do usuário que está recebendo
        if (!Auth::attempt(['email' => $request->user_receiver_email, 'password' => $request->user_receiver_password])) {
            return response()->json(['message' => 'Unauthorized - Receiver'], 401);
        }

        $userReceiverId = User::where('email', $request->user_receiver_email)->first()->id;

        $existingLoan = $this->loanService->findLoan($id);
        $existingLoanProducts = $existingLoan->loanedProducts;
        $isSameProducts = true;

        foreach ($request->products as $product) {
            $match = $existingLoanProducts->firstWhere('product_id', $product['product_id']);
            if (!$match || ( isset($product['serial_id']) && $match->product_serial_id != $product['serial_id'])) {
                $isSameProducts = false;
                break;
            }
        }

        if (!$isSameProducts) {
            $existingLoanCheck = $this->loanService->checkExistingLoan($request->products);
            if ($existingLoanCheck) {
                return response()->json(['message' => 'Item already loaned out'], 400);
            }
        }

        $loanData = [
            'user_giver_id'         => $userGiverId,
            'user_receiver_id'      => $userReceiverId,
            'product_serial_id'     => $request->product_serial_id,
            'magazines'             => $request->magazines,
            'ammunition'            => $request->ammunition,
            'products'              => $request->products
        ];

        $this->loanService->updateLoan($id, $loanData);
        return response()->json(['message' => 'Loan updated successfully']);
    }

    public function destroy($id)
    {
        $this->loanService->deleteLoan($id);
        LoanProduct::where('loan_id', $id)->delete();
        return response()->json(['message' => 'Loan deleted successfully']);
    }

    public function returnProducts(Request $request, $loanId)
    {
        $data = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => ['required', Rule::exists('products', 'id')],
            'products.*.serial_id' => ['nullable', Rule::exists('product_serials', 'id')],
        ]);

        $loan = $this->loanService->findLoan($loanId);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        foreach ($data['products'] as $product) {
            LoanProduct::where('loan_id', $loanId)
                ->where('product_id', $product['product_id'])
                ->update(['returned' => true]);
        }

        return response()->json(['message' => 'Products returned successfully']);
    }
}
