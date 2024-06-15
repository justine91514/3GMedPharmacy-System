<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Discount</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
</head>
<style>
.container-fluid {
        margin-top: 100px; /* Adjust the value as needed */
    }
    </style>
</html>
<?php 
session_start();
include('includes/header.php');
include('includes/navbar2.php');
include('dbconfig.php'); // Include your database connection file

// Fetch discount options from the database
$query = "SELECT * FROM discount_list";
$result = mysqli_query($connection, $query);
$discount_options = "";
while ($row = mysqli_fetch_assoc($result)) {
    $discount_options .= "<option value='{$row['discount_name']}'>{$row['discount_name']} - {$row['value']}%</option>";
}
?>

<!-- Modal -->
<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #259E9E; color: white; border-bottom: none;">
                <h5 class="modal-title" id="exampleModalLabel">Add Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="color: #259E9E;">Discount Name</label>
                        <input type="text" name="discount_name" class="form-control" placeholder="Input Discount" required />
                    </div>
                    <div class="form-group">
    <label for="valueInput" style="color: #259E9E;">Value (%)</label>
    <input type="number" id="valueInput" name="value" class="form-control" placeholder="Input Discount Value" required />
    <div id="valueWarning" style="display: none; color: red;">Value must be a positive number and should not start with zero.</div>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary modal-btn mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; " data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitButton" class="btn btn-primary modal-btn" name="discountbtn" style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; box-shadow: none;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this discount?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;" data-dismiss="modal">Cancel</button>
                <!-- Ensure form submission on button click -->
                <form id="deleteForm" action="code.php" method="POST">
                    <input type="hidden" id="delete_id" name="delete_id">
                    <button type="submit" name="delete_discount_btn" class="btn btn-danger" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php 
// Store discount options in session to pass to pos.php
$_SESSION['discount_options'] = $discount_options;
?>

<div class="container-fluid">
        <!-- DataTables Example -->
    <!-- DataTables Example -->
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Discount</h1>
            </div>
        <div class="card-body">

        <h6 class="m-0 font-weight-bold text-primary">
    <button type="button" class="btn btn-primary rounded-pill shadow mb-3" data-toggle="modal" data-target="#addadminprofile" style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
        <i class="fas fa-plus-square fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Add New Discount</span>
    </button>
</h6>


                
<!-- this code is for the admin profile added (makikita sa code.php)-->
<?php
if(isset($_SESSION['success']) && $_SESSION['success'] !='') 
{
    echo '<h2 class="bg-primary text-white">' .$_SESSION['success'].'</h2>';
    unset($_SESSION['success']);
}
?>
<!-- this code is for the admin profile added  -->

<!-- this code is for the Password and confirm password does not match (makikita sa code.php)-->
<?php
if(isset($_SESSION['status']) && $_SESSION['status'] !='') 
{
    echo '<h2 class="bg-danger text-white">' .$_SESSION['status'].'</h2>';
    unset($_SESSION['status']);
}
?>
<!-- this code is for the Password and confirm password does not match (makikita sa code.php)-->


            <div class="table-responsive">

            <?php
                $connection = mysqli_connect("localhost","root","","dbpharmacy");

                $query = "SELECT * FROM discount_list";
                $query_run = mysqli_query ($connection, $query);
            ?>


<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="background-color: #259E9E; color: white;">
                <th style="vertical-align: middle;"> ID </th>
<th style="vertical-align: middle;"> Discount Name </th>
<th style="vertical-align: middle;"> Value (%) </th>
<th style="vertical-align: middle;"> Action </th>
                    </thead>
                    <tbody>
                    <?php
                    if(mysqli_num_rows($query_run) > 0)
                    {
                        while($row = mysqli_fetch_assoc($query_run))
                        {
                            ?>    
                        <tr>
                        <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['discount_name']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['value']; ?></td>
<td style="vertical-align: middle;">
<form action="edit_discount.php" method="post" style="display: inline-block;">
    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
    <button type="submit" name="edit_btn" class="btn btn-action editBtn">
        <i class="fas fa-edit" style="color: #44A6F1;"></i>
    </button>
</form>
    <span class="action-divider">|</span>
    <button class="btn btn-action" style="border: none; background: none;" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $row['id']; ?>">
        <i class="fas fa-trash-alt" style="color: #FF0000;"></i>
    </button>
</td>
                        </tr>
                        <?php
                        }
                    }
                    else{
                        echo "No record Found";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
        include 'logout_modal.php';
        ?>

        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>

<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });
</script>

<script>
    var ascending = true;

    function sortTable(columnIndex) {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("dataTable");
        switching = true;
        var icon = document.getElementById("sortIcon");
        if (ascending) {
            icon.classList.remove("fa-sort");
            icon.classList.add("fa-sort-up");
        } else {
            icon.classList.remove("fa-sort-up");
            icon.classList.add("fa-sort-down");
        }
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                if (ascending) {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
        ascending = !ascending;
    }
</script>

<script>
    function validateValue() {
        var value = parseFloat(document.getElementById('valueInput').value.trim());
        var valueWarning = document.getElementById('valueWarning');
        var submitButton = document.getElementById('submitButton');

        if (value <= 0 || value.toString().startsWith('0') || value.toString().startsWith('-')) {
            valueWarning.style.display = 'block';
            submitButton.disabled = true;
        } else {
            valueWarning.style.display = 'none';
            submitButton.disabled = false;
        }
    }

    document.getElementById('valueInput').addEventListener('input', validateValue);
</script>
<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "pageLength": 10, // Display 10 entries per page
            "searching": true,
            "ordering": true, // Enable ordering/sorting
            "info": true,
            "autoWidth": false,
            "language": {
                "paginate": {
                    "previous": "<i class='fas fa-arrow-left'></i>", // Use arrow-left icon for previous button
                    "next": "<i class='fas fa-arrow-right'></i>" // Use arrow-right icon for next button
                }
            },
            "pagingType": "simple", // Set the pagination type to simple
            "columnDefs": [
                { "orderable": true, "targets": [0, 1, 2] }, // ID and Product Name columns are sortable
                { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
            ],
            "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
        });
    });
</script>
