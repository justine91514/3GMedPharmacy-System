<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
</head>
<style>

.container-custom {
        margin-top: 100px; /* Adjust the value as needed */
        padding-bottom: 20px; /* Adjust the padding at the bottom */
    }

                body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .modal-footer {
        border-top: none; /* Remove border at the top of the footer */
        padding: 15px 20px; /* Add padding */
        background-color: #f8f9fc; /* Footer background color */
        border-radius: 0 0 10px 10px; /* Rounded corners only at the bottom */
    }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #259E9E;
        }

        input[type="text"],
        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        /* Applying custom styles to the select element */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .modal-label {
            color: #259E9E;
        }
        .container-wrapper {
    padding-bottom: 50px; /* Adjust the padding at the bottom */
}
    </style>
</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include ('includes/header.php');
include ('includes/navbar2.php');
?>
        <div class="container-fluid container-custom">
        <div class="container-wrapper">
            <div class="card shadow nb-4">
            <div class="card-header py-3" style="background-color: #259E9E; color: white; border-bottom: none;">
    <h6 class="m-0 font-weight-bold" style="color: white;">Edit Category</h6>
</div>
        <div class="card-body">
            <?php
            $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];

                $query = "SELECT * FROM category_list WHERE id='$id'";
                $query_run = mysqli_query($connection, $query);

                foreach ($query_run as $row) {
                    ?>
                    <form action="code.php" method="POST"> <!-- Moved the form tag opening here -->

                        <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                        <div class="form-group">
                            <label> Category </label>
                            <input type="text" name="edit_category" value="<?php echo $row['category_name'] ?>"
                                class="form-control" placeholder="Input Category" required />
                        </div>
                    <div class="modal-footer" style="background-color: #ffff; border-top: 1px solid #ccc;">
    <a href="add_category.php" class="btn btn-danger" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 8px; margin-bottom: -15px;">Cancel</a>
    <button type="submit" name="updatecategorybtn" class="btn btn-primary modal-btn" style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; margin-top: 8px; margin-bottom: -15px;">Update</button>
                    </form>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
</div>
<?php
include ('includes/scripts.php');
include ('includes/footer.php');
?>
<!-- Logout Modal Popup + Logout Action -->
<?php
include 'logout_modal.php';
?>