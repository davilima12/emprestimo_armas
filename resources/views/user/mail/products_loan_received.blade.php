<!DOCTYPE html>
<html>
<head>
    <title>Recibo de Empréstimo</title>
</head>
<body style="background-color: #f4f4f4; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="background-color: white; margin-left:auto; margin-right:auto; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 600px; padding: 20px; font-family: Arial, sans-serif; max-height: 600px;">

        <div style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #3498db;">{{ config('app.name') }}</h2>
        </div>

        <div>
            <p>Olá, <strong>{{ $userReceiverName }}</strong>,</p>
            <p>Você recebeu os seguintes produtos emprestados:</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px;">Produto</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Número de série</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Munições</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Carregadores</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loanedProducts as $product)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->product->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $product?->productSerial?->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->ammunition }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->magazines }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top: 20px;">Este recibo é referente ao empréstimo realizado em <strong>{{ $loanDate }}</strong>.</p>

            <div style="margin-top: 30px;">
                <p>Se você tiver dúvidas, entre em contato conosco.</p>
            </div>
        </div>

        <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 20px; text-align: center; font-size: 12px; color: #777;">
            <p>Atenciosamente,<br>Equipe {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
