<?php
// payment.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PawaPay Payment</title>

<style>

body{
    font-family:Arial,Helvetica,sans-serif;
    background:#f4f4f4;
    margin:0;
    padding:40px;
}

.container{
    width:420px;
    margin:auto;
    background:#fff;
    padding:25px;
    border-radius:8px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:15px;
}

label{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
}

input,select{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:4px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    border:none;
    background:#28a745;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    border-radius:4px;
}

button:hover{
    background:#218838;
}

</style>

</head>
<body>

<div class="container">

<h2>PawaPay Test Payment</h2>

<form action="process_payment.php" method="POST">

<div class="form-group">
<label>Customer Name</label>
<input
type="text"
name="customer_name"
required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input
type="text"
name="phone"
placeholder="2507XXXXXXXX"
required>
</div>

<div class="form-group">
<label>Country</label>
<select name="country" required>

<option value="RWA">Rwanda</option>
<option value="UGA">Uganda</option>
<option value="KEN">Kenya</option>
<option value="TZA">Tanzania</option>
<option value="ZMB">Zambia</option>

</select>
</div>

<div class="form-group">
<label>Currency</label>
<select name="currency">

<option value="RWF">RWF</option>
<option value="UGX">UGX</option>
<option value="KES">KES</option>
<option value="TZS">TZS</option>
<option value="ZMW">ZMW</option>

</select>
</div>

<div class="form-group">
<label>Amount</label>
<input
type="number"
step="0.01"
name="amount"
required>
</div>

<button type="submit">
Pay Now
</button>

</form>

</div>

</body>
</html>