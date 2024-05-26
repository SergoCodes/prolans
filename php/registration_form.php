<div class="registration-heading">
    <h2>Регистрация</h2>
    <h4 class = "regular">Оставьте заявку и мы обязательно с Вами свяжемся!</h4>
</div>
<div class="registration-form-container">
    <div class = "form-wrap">
    <div class="student-form">
        <h3>Учащийся</h3>
        <form action = "index.php?page=registration_form&action=add_application" method="POST">
            <label for="stName" class = "h4" >Ваше имя</label>
            <input type="text" name="stName">
            <label for="stSurname" class = "h4">Ваша фамилия</label>
            <input type="text" name="stSurname">
            <label for="stPatronym" class = "h4">Ваше отчество</label>
            <input type="text" name="stPatronym">
            <label for="stBirthyear" class = "h4">Год рождения</label>
            <select name="stBirthyear">
                <?php
                $end_year = date("Y") - 8;
                $start_year = date("Y") - 25;
                for ($year = $end_year; $year >= $start_year; $year--) {
                    echo '
                    <option value="' . $year . '">' . $year . '
                    </option>';
                }
                ?>
            </select>

            <label for="chosen_courses" class = "h4">Выберите курс:</label>
            <select name="chosen_courses[]" multiple>
                <?php
                $sqltext = "SELECT ID, code, name FROM Courses";
                $result = mysqli_query($db, $sqltext);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <option value = "' .$row["code"] . '"> 
                        [' . $row["code"]. '] 
                        ' . $row["name"]. '
                    </option>';
                }
                ?>
            </select>

            <label for="stNumber" class = "h4">Телефон</label>
            <input type="number" name="stNumber">
            </div>
            <div class="parent-form">
            <h3>Родитель</h3>

            <label for="prName" class = "h4">Ваше имя</label>
            <input type="text" name="prName">
            <label for="prSurname" class = "h4">Ваша фамилия</label>
            <input type="text" name="prSurname" class = "h4">
            <label for="prPatronym"class = "h4">Ваше отчество</label>
            <input type="text" name="prPatronym">
            <label for="prNumber" class = "h4">Телефон</label>
            <input type="number" name="prNumber">
            <label for="prEmail" class = "h4">Email</label>
            <input type="text" name="prEmail">
            </div>
            </div>
            <input type="submit" value="Отправить" class= "button">
        </form>
    </div>
       
</div>

<?php
    date_default_timezone_set('Europe/Moscow');

    // Inserting a new record into Applications
    if(!empty($_GET['action'])&&($_GET['action']=='add_application')) {
        $sqltext = 'INSERT INTO applications 
        (stName, stSurname, stPatronym, stBirthyear,
         stNumber, prName, prSurname, prPatronym, prNumber, prEmail, date) 
            VALUES ("'.
            $_POST['stName'] .'","'.
            $_POST['stSurname'].'","'.
            $_POST['stPatronym'].'","'.
            intval($_POST['stBirthyear']).'","'. 
            intval($_POST['stNumber']).'","'.
            $_POST['prName'].'","'.
            $_POST['prSurname'].'","'.
            $_POST['prPatronym'].'","'.
            intval($_POST['stNumber']).'","'.
            $_POST['prEmail'].'","'.
            date("Y-m-d H:i:s").'");';

        mysqli_query($db,$sqltext);   

        // Inserting a new record into CoursesApplications
        $applicationID = mysqli_insert_id($db);

        foreach ($_POST['chosen_courses'] as $courseCode) {

            $sqltext = "SELECT ID FROM Courses WHERE code = '{$courseCode}'";
            $result = mysqli_query($db, $sqltext);
            $row = mysqli_fetch_assoc($result);
            $courseID = $row['ID'];

            $sql = "INSERT INTO CoursesApplications (applicationID, courseID) VALUES ({$applicationID}, {$courseID})";
            mysqli_query($db, $sql);

        }
    }
?>