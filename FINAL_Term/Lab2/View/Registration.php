<?php
include "../Controller/RegistrationController.php";
?>
<!DOCTYPE html>
<html>
    <body>
        <form method = "post" action="">
            <table>
                <tr>
                    <td> <label for ="Username">User Name: </label></td>
                    <td> <input type ="text" id="name" name="name"> <?php echo $name ?> </td>
                </tr>
                <tr>
                    <td> <label for ="password">Password:  </label></td>
                    <td> <input type ="password" id ="pass" name ="password"> <?php echo $password ?></td>
                </tr>
                <tr>
                    <td> <input type ="submit" id="submitbutton" name = "submit"> </td>
                </tr>
            </table>
        </form>
    </body>
</html>