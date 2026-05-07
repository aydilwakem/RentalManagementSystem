<!DOCTYPE html>
<html>

<head>
    <title>GCash Payment</title>
</head>

<body>
    <h2>Pay ₱1,000.00 via GCash</h2>
    <form action="/pay" method="POST">
        @csrf
        <button type="submit">Pay Now</button>
    </form>
</body>

</html>