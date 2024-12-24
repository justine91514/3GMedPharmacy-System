<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Other scripts you have in your scripts.php file -->
<!-- Core plugin JavaScript -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages -->
<script src="js/sb-admin-2.min.js"></script>
<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

<!-- Add these links to include Bootstrap DateTimePicker CSS and JS files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



<script>
    // Update the notification badge count in the header
    document.addEventListener('DOMContentLoaded', function () {
        const expiringSoonCount = <?php echo $expiring_soon_count + $expiring_soon_buffer_count; ?>;
        const expiredCount = <?php echo $expired_count; ?>;
        const lowstockCount = <?php echo $low_stock_count; ?>;
        const outstockCount = <?php echo $out_count; ?>;
        const badgeCounter = document.querySelector('.badge-counter');

        if (badgeCounter) {
            const totalCount = expiringSoonCount + expiredCount + lowstockCount + outstockCount;
            badgeCounter.innerHTML = totalCount > 0 ? totalCount : '';
        }

        const expiringSoonLink = document.getElementById('expiringSoonLink');
        const expiredLink = document.getElementById('expiredLink');
        const lowstockLink = document.getElementById('lowstockLink');
        const outstockLink = document.getElementById('outstockLink');

        if (expiringSoonLink) {
            expiringSoonLink.addEventListener('click', function () {
                // Redirect to add_stocks.php when "Expiring Soon in Stocks" is clicked
                window.location.href = 'add_stocks.php';
            });
        }

        if (lowstockLink) {
            lowstockLink.addEventListener('click', function () {
                // Redirect to add_stocks.php when "Expiring Soon in Stocks" is clicked
                window.location.href = 'add_stocks.php';
            });
        }

        if (expiringSoonBufferLink) {
            expiringSoonBufferLink.addEventListener('click', function () {
                // Redirect to buffer_stocks.php when "Expiring Soon in Buffer" is clicked
                window.location.href = 'buffer_stocks.php';
            });
        }

        if (expiredLink) {
            expiredLink.addEventListener('click', function () {
                // Redirect to expired_products.php when "Expired Products" is clicked
                window.location.href = 'expired_products.php';
            });
        }
        if (outstockLink) {
            outstockLink.addEventListener('click', function () {
                // Redirect to expired_products.php when "Expired Products" is clicked
                window.location.href = 'out_of_stock.php';
            });
        }
    });
</script>

<!--/************************
            Code for Void In Pos
    ************************/ -->
    <script>
        $(document).ready(function () {
            var voidButtonClicked = false;

            // Deactivate the "Void" button initially
            $('#voidButton').prop('disabled', true);
            $('#delete_void_Button').hide(); // Hide the Delete button initially
            $('#voidButton').click(function () {
                if ($(this).text() === 'Void') {
                    voidButtonClicked = false;
                    $(this).text('Cancel Void');
                    $('#productInfoLabel').text('Product Name:');
                    $('#productquantLabel').text('Quantity:');

                    $('#productStocksLabel').text('Stocks Available:');
                    $('#productpriceLabel').text('Price:');

                    $('#barcode').val('');
                    $('#descript').val('');
                    $('#price').val('');
                    $('#quantity').val('');
                    enableTableRowSelection();

                    $('#delete_void_Button').show();
                } else {
                    voidButtonClicked = true;
                    $(this).text('Void');
                    $('#productInfoLabel').text('Barcode:');
                    $('#productquantLabel').text('Description:');
                    $('#productStocksLabel').text('Price:');
                    $('#productpriceLabel').text('Quantity:');

                    $('#barcode').val('');
                    $('#descript').val('');
                    $('#price').val('');
                    $('#quantity').val('');
                    $('#delete_void_Button').hide();
                    enableTableRowSelection();
                }
            });
            // Check the content of #scannedItems before enabling or disabling the "Void" button
            function checkTableContent() {
                if ($('#scannedItems tr').length > 0) {
                    $('#voidButton').prop('disabled', false);
                } else {
                    $('#voidButton').prop('disabled', true);
                }
            }
            function disableTableRowSelection() {
                $('#scannedItems').off('click', 'tr'); // Turn off the event listener for clicking on table rows
                $('#scannedItems tr').removeClass('selected'); // Remove the selected class from all rows
                $('#barcode').val('');
                $('#descript').val('');
                $('#price').val('');
                $('#quantity').val('');
            }
            function enableTableRowSelection() {
                $('#scannedItems').on('click', 'tr', function () {
                    if (!voidButtonClicked) {
                        if (!$(this).hasClass('selected')) {
                            $('#barcode').val('');
                            $('#descript').val('');
                            $('#price').val('');
                            $('#quantity').val('');
                        }
                        $(this).toggleClass('selected').siblings().removeClass('selected');
                        if ($(this).hasClass('selected')) {
                            var productNameWithMeasurement = $(this).find('td:eq(0)').text();
                            var quantity = $(this).find('td:eq(1)').text();
                            var stocksAvailable = $(this).find('td:eq(3)').text();
                            var price = $(this).find('td:eq(4)').text();

                            $('#barcode').val(productNameWithMeasurement);
                            $('#descript').val(quantity);
                            $('#price').val(stocksAvailable);
                            $('#quantity').val(price);
                        }
                    }
                });
            }
            // Call the checkTableContent function whenever there is a change in table content
            $('#scannedItems').on('DOMSubtreeModified', function () {
                checkTableContent();
            });
            // Check the table content initially
            checkTableContent();
        });

        //code for delete void button
        $(document).ready(function () {
            // Event listener for the Delete button
            $('#delete_void_Button').click(function () {
                // Confirm if any row is selected
                if ($('#scannedItems tr.selected').length >= 0) {
                    // Remove each selected row
                    $('#scannedItems tr.selected').remove();
                    // After deletion, clear the selected fields
                    var totalAmount = 0;
                    $('#scannedItems tr').each(function () {
                        var quantity = parseFloat($(this).find('td:eq(1)').text());
                        var price = parseFloat($(this).find('td:eq(4)').text());
                        totalAmount += quantity * price;

                    });
                    // Update Sub Total
                    $('#sub_total').val(totalAmount.toFixed(2));
                    // Check if there's a discount applied
                    var discountValue = parseFloat($('#discountSelect').val());
                    if (!isNaN(discountValue)) {
                        var discountedAmount = totalAmount - (totalAmount * (discountValue / 100));
                        $('#total').val(discountedAmount.toFixed(2));
                    } else {
                        $('#total').val(totalAmount.toFixed(2));
                    }
                    // Recalculate change
                    calculateChange();
                }
            });
        });
    </script>










