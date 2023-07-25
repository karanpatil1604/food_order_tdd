<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order - Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid mt-5">

        @foreach ($orders as $order)
            <div class="mx-auto" style="width: 450px;">

                <div class="text-center">
                    <h3>You paid Rs. {{ $order['total'] }}</h3>
                    <span>Please wait while we prepare your order.</span>
                </div>


                <div class="mt-5">
                    <h6 class="text-center">Order Summary</h6>

                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->detail as $item)
                                <tr>
                                    <td>{{ $item->product_id }}</td>
                                    <td>Rs. {{ $item->cost }}</td>
                                    <td>{{ $item->qty }}x</td>
                                    <td>$ {{ $item->qty * $item->cost }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        @endforeach

    </div>

</body>

</html>
