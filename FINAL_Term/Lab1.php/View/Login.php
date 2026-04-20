<!DOCTYPE html>
<html>
    <body>
        <form method='post' action="../Controler/loginvalidation.php">
            <?php
            echo "<h1 style='color: red'> Login Page</h1>"
            ?>
            <table>
                <tr>
                    <td>User name:</td>
                    <td><input type="text"></td>
                </tr>
                <tr>
                    <td>Passweor:</td>
                    <td><input type="text"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit">
                    </td>
                </tr>

            </table>
        </form>
        <?php
            echo "<h4 style='color: red'> Login Introduction</h4>";
            $text1="Hello World";
            echo "<b> $text1</b>";
            echo"<br>";
            echo '<b>'.$text1.'</b>';

            $var1=10.6;
            $var2=30;
            echo"<br>";
            echo"Sum: ";
            echo $var1+$var2;
            echo"<br>";

            //Array
            $cars= array("WebTech","OOP2","Python");
            var_dump($cars);

            $course= array("Course:"=>"WebTech","PreRequsite"=>"OOP2","NextCourse: "=>"Python");
            var_dump($course);
            echo"<br>";
            echo $course["Course:"];
            echo"<br>";

            //conditionala statement
            if($var1>0){
                echo"Possitive";
            }
            else{
                echo "negative";
            }

            function addnunmber($var1, $var2){
                return $var1+$var2;
            }



            ?>
    </body>
</html>