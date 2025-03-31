<div id="deposit-tab">
    <h3>Deposit</h3>
    <form method="POST">
        <label for="deposit_month">Select Month:</label>
        <select name="deposit_month" required>
            <option value="">-- Select Month --</option>
            <?php
            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            foreach ($months as $month) {
                echo "<option value='$month'>$month</option>";
            }
            ?>
        </select>

        <label for="category">Category:</label>
        <select id="deposit-category" name="category" required>
            <option value="Monthly Deposit">Monthly Deposit</option>
            <option value="Other">Other</option>
        </select>

        <div id="subcategory-container" style="display:none;">
            <label for="subcategory">Subcategory:</label>
            <input type="text" name="subcategory" placeholder="Enter subcategory name">
        </div>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" placeholder="Deposit Amount" required>

        <input type="hidden" name="type" value="deposit">
        <button type="submit" name="submit_transaction">Submit</button>
    </form>
</div>

<script>
document.getElementById('deposit-category').addEventListener('change', function() {
    document.getElementById('subcategory-container').style.display = (this.value === 'Other') ? 'block' : 'none';
});
</script>
