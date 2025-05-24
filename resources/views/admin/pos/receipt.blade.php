<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupom Fiscal - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }

        .order-info {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .customer-info {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .customer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .items-header {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: bold;
            display: flex;
        }

        .item-qty {
            width: 15%;
        }

        .item-name {
            width: 55%;
        }

        .item-price {
            width: 30%;
            text-align: right;
        }

        .item-row {
            display: flex;
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-sku {
            font-size: 9px;
            color: #666;
            margin-left: 15%;
            margin-bottom: 3px;
        }

        .totals {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payment-info {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 5px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="receipt-header">
        <div class="company-name">LUMIS ERP</div>
        <div class="company-info">Sistema de Gestão Empresarial</div>
        <div class="company-info">www.lumiserp.com</div>
        <div class="receipt-title">CUPOM FISCAL</div>
    </div>

    <!-- Informações do Pedido -->
    <div class="order-info">
        <div class="info-row">
            <span>Pedido:</span>
            <span>{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span>Data:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span>Vendedor:</span>
            <span>{{ $order->user->name }}</span>
        </div>
        <div class="info-row">
            <span>Pagamento:</span>
            <span>
                @switch($order->payment_method)
                    @case('cash') Dinheiro @break
                    @case('card') Cartão @break
                    @case('pix') PIX @break
                    @case('bank_transfer') Transferência @break
                    @default {{ $order->payment_method }}
                @endswitch
            </span>
        </div>
    </div>

    <!-- Informações do Cliente -->
    @if($order->customer)
    <div class="customer-info">
        <div class="customer-title">CLIENTE</div>
        <div class="info-row">
            <span>Nome:</span>
            <span>{{ $order->customer->name }}</span>
        </div>
        <div class="info-row">
            <span>{{ $order->customer->type === 'PF' ? 'CPF' : 'CNPJ' }}:</span>
            <span>{{ $order->customer->document }}</span>
        </div>
        @if($order->customer->email)
        <div class="info-row">
            <span>Email:</span>
            <span>{{ $order->customer->email }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Itens -->
    <div class="items-header">
        <div class="item-qty">QTD</div>
        <div class="item-name">PRODUTO</div>
        <div class="item-price">TOTAL</div>
    </div>

    @foreach($order->items as $item)
    <div class="item-row">
        <div class="item-qty">{{ $item->quantity }}</div>
        <div class="item-name">{{ $item->product_name }}</div>
        <div class="item-price">{{ $item->formatted_total_price }}</div>
    </div>
    <div class="item-sku">{{ $item->product_sku }} - {{ $item->formatted_unit_price }}</div>
    @endforeach

    <!-- Totais -->
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ $order->formatted_subtotal }}</span>
        </div>
        
        @if($order->discount > 0)
        <div class="total-row">
            <span>Desconto:</span>
            <span>-R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
        </div>
        @endif

        @if($order->shipping > 0)
        <div class="total-row">
            <span>Frete:</span>
            <span>R$ {{ number_format($order->shipping, 2, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>{{ $order->formatted_total }}</span>
        </div>
    </div>

    <!-- Informações de Pagamento -->
    <div class="payment-info">
        <strong>PAGO</strong><br>
        Forma: 
        @switch($order->payment_method)
            @case('cash') DINHEIRO @break
            @case('card') CARTÃO @break
            @case('pix') PIX @break
            @case('bank_transfer') TRANSFERÊNCIA @break
            @default {{ strtoupper($order->payment_method) }}
        @endswitch
    </div>

    @if($order->notes)
    <div style="margin-top: 10px; border-top: 1px dashed #000; padding-top: 10px;">
        <strong>Observações:</strong><br>
        {{ $order->notes }}
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div style="margin-bottom: 10px;">
            Obrigado pela preferência!<br>
            Volte sempre!
        </div>
        <div style="font-size: 9px; color: #666;">
            Documento não fiscal<br>
            {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Botões de Ação (não imprime) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; margin: 5px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Fechar
        </button>
    </div>

    <script>
        // Auto-print quando a página carrega
        window.onload = function() {
            // Aguarda um pouco para garantir que tudo carregou
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 