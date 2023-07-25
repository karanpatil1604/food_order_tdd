<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order - Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container-fluid mt-5">

        <div class="mx-auto" style="width: 350px;">


            <div class="mt-3">
                @if ($cart_items && count($cart_items) > 0)

                    @foreach ($cart_items as $item)
                        <div class="card mb-3 overflow-hidden" style="max-width: 540px; max-height: 145px;">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="https://images.unsplash.com/photo-1604467715878-83e57e8bc129?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=388&q=80"
                                        class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">

                                        <div class="float-start">
                                            <h5 class="card-title m-0 p-0">{{ $item['name'] }}</h5>
                                            <span>Rs. {{ $item['cost'] }}</span>
                                        </div>

                                        <div class="float-end">
                                            <form action="/cart/{{ $item['id'] }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link">Remove</button>
                                            </form>
                                        </div>

                                        <div class="clearfix"></div>

                                        <form action="/cart/{{ $item['id'] }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mt-1 row">
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-secondary btn-sm">-</button>
                                                </div>
                                                <div class="col-auto">
                                                    <input class="form-control form-control-sm" type="text"
                                                        name="qty" value="{{ $item['qty'] }}"
                                                        style="width: 100px;">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-secondary btn-sm">+</button>
                                                </div>

                                                <div class="mt-2 d-grid">

                                                    <button type="submit"
                                                        class="btn btn-secondary btn-sm">Update</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-grid gap-2">
                        <a class="d-block" href="/checkout"><button class="btn btn-primary"
                                type="button">Checkout</button></a>
                    </div>
                @else
                    <div>Cart Is Empty.</div>
                @endif
            </div>

        </div>

    </div>

    <script src="{{ mix('js/cart.js') }}" defer></script>
</body>

</html>
