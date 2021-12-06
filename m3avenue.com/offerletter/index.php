<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Offer Letter</title>
</head>
<body>
    <div id="container">
        <h2>Offer Letter Generation</h2>
        <form action="offerletter.php" method="POST">
            <!-- Date -->
            <p>
                <h3 style="display: inline;">Date:</h3>
                <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required/>
            </p>
            <!-- Name -->
            <p>
                <h3 style="display: inline;">First Name:</h3>
                <input type="text" name="first_name" id="first_name" placeholder="First name" required >

                <h3 style="display: inline; padding-left: 2%;">Last Name:</h3>
                <input type="text" name="last_name" id="last_name" placeholder="Last name" required >
            </p>

            <!-- Father name -->
            <p>
                <h3 style="display: inline;">Father Name:</h3>
                <input type="text" name="father_name" id="father_name" placeholder="Father name" required >
            </p>

            <!-- Address -->
            <div class="address_field">
                    <h3 class="add_heading">Address</h3>
                <p>
                    <div>
                        <h3 style="display: inline;">House No:</h3>
                        <input type="text" name="house_no" id="house_no" placeholder="House_no" >
                    </div>
                    
                    <div>
                        <h3 style="display: inline;">Street:</h3>
                        <input type="text" name="street" id="street" placeholder="Street" >
                    </div>
                    
                    <div>
                        <h3 style="display: inline;">City:</h3>
                        <input type="text" name="city" id="city" placeholder="City" >
                    </div>
                    
                    <div>
                        <h3 style="display: inline;">District:</h3>
                        <input type="text" name="district" id="district" placeholder="District">
                    </div>

                    <div>
                        <h3 style="display: inline;">Pincode:</h3>
                        <input type="number" name="pin" id="pin" placeholder="Pincode" min="0" >
                    </div>
                </p>
            </div>
            
            <p>
                <h3 style="display: inline;">Joining Date:</h3>
                <input type="date" name="join_date" id="join_date" value="<?php echo date('Y-m-d'); ?>" required/>
            </p>
            <!-- Payment Type -->
            <p>
                <h3 style="display: inline;">Payment type: </h3>
                <select name="payment" id="payment" required>
                    <option value="">Select</option>
                    <option value="review_pay">Review Pay</option>
                    <option value="variable_pay">Variable Pay</option>
                    <option value="royalty_pay">Royalty Pay</option>
                </select>
            </p>
            <p>
                <h3 style="display: inline;" class="ctc-head">CTC Amount: </h3>
                <input type="number" name="ctc" id="ctc" placeholder="Disabled" min="0" disabled>
            </p>
            <!-- Designation -->
            <p>
                <h3 style="display: inline;">Designation: </h3>
                <select name="designation" id="designation" required>
                    <option value="">Select</option>
                    <option value="Telecaller">Telecaller</option>
                    <option value="Business Development Associate">Business Development Associate</option>
                    <option value="Digital Marketing Executive">Digital Marketing Executive</option>
                    <option value="HR Manager">HR Manager</option>
                    <option value="Team Leader">Team Leader</option>
                </select>
            </p>

            <!-- Location -->
            <p>
                <h3 style="display: inline;">Location:</h3>
                <input type="text" name="location" id="location" placeholder="Location" value="Remote" required>
            </p>

            <!-- Submit Button -->
            <p class= "ll">
                <input type="submit" name="submit" class="submit-button">
            </p>
        </form>
    </div>
    <script>
        
        document.getElementById('payment').onchange = function () 
        {
            if (this.value == 'royalty_pay') 
            {
                document.getElementById("ctc").disabled = true;
                document.getElementsByName('ctc')[0].placeholder='Disabled';
            }

            else 
            {
                document.getElementById("ctc").disabled= false;
                document.getElementsByName('ctc')[0].placeholder='CTC';
            }
        }
    </script>
</body>
</html>