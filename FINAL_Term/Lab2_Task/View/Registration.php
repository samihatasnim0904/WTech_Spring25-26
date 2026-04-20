<?php
// Initialize variables to avoid undefined errors
$nameErr = $emailErr = $websiteErr = $genderErr = "";
$name = $email = $website = $comment = $gender = "";

// Include controller safely
include_once __DIR__ . "/../Controller/RegistrationController.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

<h2>Registration Form</h2>
<p><span style="color:red">* required field</span> </p>

<form method="post" action="">
    <table>

        <tr>
            <td>Name:</td>
            <td>
                <input type="text" name="name" value="<?php echo $name; ?>">
                <span><?php echo $name; ?></span>
                <span style="color:red">*</span>
                <span style="color:red"><?php echo $nameErr; ?></span>
            </td>
        </tr>

        <tr>
            <td>E-mail:</td>
            <td>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email; ?></span>
                <span style="color:red">*</span>
                <span style="color:red"><?php echo $emailErr; ?></span>
            </td>
        </tr>

        <tr>
            <td>Website:</td>
            <td>
                <input type="text" name="website" value="<?php echo $website; ?>">
                <span><?php echo $website; ?></span>
                <span style="color:red"><?php echo $websiteErr; ?></span>
            </td>
        </tr>

        <tr>
            <td>Comment:</td>
            <td>
                <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea>
                <span><?php echo $comment; ?></span>
            </td>
        </tr>

        <tr>
            <td>Gender:</td>
            <td>
                <input type="radio" name="gender" value="Female"
                    <?php if($gender=="Female") echo "checked"; ?>> Female

                <input type="radio" name="gender" value="Male"
                    <?php if($gender=="Male") echo "checked"; ?>> Male

                <input type="radio" name="gender" value="Other"
                    <?php if($gender=="Other") echo "checked"; ?>> Other

                <span style="color:red">*</span>
                <span style="color:red"><?php echo $genderErr; ?></span>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Submit">
            </td>
        </tr>

    </table>
</form>

</body>
</html>