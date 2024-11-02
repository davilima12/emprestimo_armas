<!DOCTYPE html>
<html>

<head>
    <title>Recibo de Empréstimo</title>
    <style>
        .sub-table {
            display: none; /* Escondido por padrão */
            margin-top: 10px;
            border: 1px solid #ddd;
            border-collapse: collapse;
            width: 100%;
        }

        .sub-table th,
        .sub-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .toggle-button {
            cursor: pointer;
            color: #3498db;
            text-decoration: underline;
        }

        .loan-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            background-color: #fff;
        }
    </style>
    <script>
        function toggleSubTable(index) {
            const subTable = document.getElementById('sub-table-' + index);
            subTable.style.display = subTable.style.display === 'none' ? 'table' : 'none';
        }
    </script>
</head>

<body style="background-color: #f4f4f4; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="background-color: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 800px; padding: 20px; font-family: Arial, sans-serif;">

        <div style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #3498db;">{{ config('app.name') }}</h2>
        </div>

        @foreach ($loans as $index => $loan)
        <div class="loan-container">
            <table>
                <thead>
                    <tr>
                        <th><strong>Empréstimo realizado por:</strong></th>
                        <th><strong>Produto retirado por:</strong></th>
                        <th><strong>Data do empréstimo:</strong></th>
                        <th><strong>Recebido por:</strong></th>
                        <th><strong>Data de devolução:</strong></th>
                    </tr>
                </thead>
                <tr>
                    <td>{{ $loan?->userGiver?->name }}</td>
                    <td>{{ $loan?->userReceiver?->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $loan?->userReceipt?->name }}</td>
                    <td>{{ $loan?->receipt_date ? \Carbon\Carbon::parse($loan->receipt_date)->format('d/m/Y') : '' }}</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Número de série</th>
                                    <th>Munições</th>
                                    <th>Carregadores</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loan->loanedProducts as $product)
                                <tr>
                                    <td onclick="toggleSubTable({{ $index }})" class="toggle-button">{{ $product->product->name }}</td>
                                    <td>{{ $product?->productSerial?->serial_number }}</td>
                                    <td>{{ $product->ammunition }}</td>
                                    <td>{{ $product->magazines }}</td>
                                </tr>
                                <tr id="sub-table-{{ $index }}" class="sub-table">
                                    <td colspan="4">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #ddd; padding: 8px;">Detalhes do Produto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid #ddd; padding: 8px;">
                                                        <strong>Descrição:</strong> {{ $product->product->description }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        @endforeach

        <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 20px; text-align: center; font-size: 12px; color: #777;">
            <p>Atenciosamente,<br>Equipe {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>

</html>
