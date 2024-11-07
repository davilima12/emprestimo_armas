<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\LoanReceiptEmail;
use App\Mail\LoanSummaryEmail;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\User;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function store(Request $request): JsonResponse
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

        try {
            // Autenticação do usuário que está emprestando
            $userGive = User::checkUser($request->giver_email, $request->giver_password);

            // Autenticação do usuário que está recebendo
            User::checkUser($request->user_receiver_email, $request->user_receiver_password);

            $userReceiverId = User::where('email', $request->user_receiver_email)->first()->id;

            $existingLoan = $this->loanService->checkExistingLoan($request->products);

            if ($existingLoan) {

                return response()->json(['message' => 'Item already loaned out'], 400);
            }
            DB::beginTransaction();
            $loanData = [
                'user_giver_id' => $userGive->id,
                'user_receiver_id' => $userReceiverId,
                'product_serial_id' => $request->product_serial_id,
                'magazines' => $request->magazines,
                'ammunition' => $request->ammunition,
            ];

            $loan = $this->loanService->createLoan($loanData);

            $this->loanService->addLoanProducts($loan->id, $request->products);

            DB::commit();
            $loan = Loan::with('userReceiver', 'loanedProducts.product')->find($loan->id);

            Mail::to($loan->userReceiver->email)->send(new LoanReceiptEmail($loan));

            return response()->json($loan, 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erro ao realizar a transação: '.$e->getMessage());

            return response()->json(
                ['message' => $e->getMessage()], 400);
        }
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
        DB::beginTransaction();
        try {
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
                if (!$match || (isset($product['serial_id']) && $match->product_serial_id != $product['serial_id'])) {
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
                'products'              => $request->products,
            ];

            $this->loanService->updateLoan($id, $loanData);

            DB::commit();

            $loan = Loan::with('userReceiver', 'loanedProducts.product')->find($id);

            Mail::to($loan->userReceiver->email)->send(new LoanReceiptEmail($loan));

            return response()->json(['message' => 'Loan updated successfully']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erro ao realizar a transação: '.$e->getMessage());

            return response()->json($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->loanService->deleteLoan($id);
            LoanProduct::where('loan_id', $id)->delete();

            return response()->json(['message' => 'Loan deleted successfully']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erro ao realizar a transação: '.$e->getMessage());

            return response()->json($e->getMessage(), 400);
        }
    }

    public function returnProducts(Request $request, $loanId)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate([
                'products' => 'required|array',
                'products.*.product_id' => ['required', Rule::exists('products', 'id')],
                'products.*.serial_id' => ['nullable', Rule::exists('product_serials', 'id')],
                'user_receiver_email' => 'required|email',
                'user_receiver_password' => 'required|string',
                'giver_email' => 'required|email',
                'giver_password' => 'required|string',
            ]);

            $loan = $this->loanService->findLoan($loanId);
            if (!$loan) {
                return response()->json(['message' => 'Loan not found'], 404);
            }

            // Autenticação do usuário que está emprestando
            if (!Auth::attempt(['email' => $request->giver_email, 'password' => $request->giver_password])) {
                return response()->json(['message' => 'Unauthorized - Giver'], 401);
            }

            $userGiverId = Auth::id();

            // Autenticação do usuário que está recebendo
            if (!Auth::attempt(['email' => $request->user_receiver_email, 'password' => $request->user_receiver_password])) {
                return response()->json(['message' => 'Unauthorized - Receiver'], 401);
            }

            foreach ($data['products'] as $product) {
                LoanProduct::where('loan_id', $loanId)
                    ->where('product_id', $product['product_id'])
                    ->update(['returned' => true]);
            }

            $loan->update(
                [
                    'user_receipt_id' => $userGiverId,
                    'receipt_date' => now()->format('Y-m-d H:i:s'),
                ]
            );
            DB::commit();

            return response()->json(['message' => 'Products returned successfully']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erro ao realizar a transação: '.$e->getMessage());

            return response()->json($e->getMessage(), 400);
        }
    }

    public function loansByUser(int $user_id, $filters = [])
    {
        $query = Loan::with(['userGiver', 'userReceipt', 'userReceiver', 'loanedProducts.product', 'loanedProducts.productSerial'])
            ->where('user_giver_id', $user_id);

        if (!isset($filters['start_date'])) {
            $query = $query->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()]);
        } elseif (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query = $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->get();
    }

    public function sendMailLoansByUser(Request $request, int $user_id)
    {
        $filters = $request->all();
        $user = User::find($user_id);

        $loans = $this->loansByUser($user_id, $filters);

        if ($loans->isEmpty()) {
            return response()->json(['message' => 'Nenhum empréstimo encontrado para enviar.'], 404);
        }

        Mail::to($user->email)->send(new LoanSummaryEmail($loans, $user));

        return response()->json(['message' => 'E-mail enviado com sucesso']);
    }
}
