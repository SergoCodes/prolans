<?php
    $sqlApplications = "select * from Applications";

		if($admin && $aRes = mysqli_query($db,$sqlApplications)) {
				print('
					<div class="applications-container">
						<h3 class="text">Заявки:</h3>');
                if($admin && !empty($_GET['action']) && 
                ($_GET['action']=='update_application' && !empty($_GET['ID']))) {
                    print('<br/><br/><p>Заявка изменена</p><br/>');
                }
				while($k = mysqli_fetch_array($aRes)) {
                    $sqltext = 'select code, name from CoursesApplications join courses on CoursesApplications.courseID = Courses.ID where CoursesApplications.applicationID = "'.$k['ID'].'"';
                    $res = mysqli_query($db,$sqltext);
					print('
                        <div class = "application-card-wrap">
                            <div class="application-card" style: "display: block;">
                                <p><span>Дата подачи заявки:</span> '.$k['date'].'</p>
                                <p><span>Имя студента:</span> '.$k['stName'].'</p>
                                <p><span>Фамилия студента:</span> '.$k['stSurname'].'</p>
                                <p><span>Отчетство студента:</span> '.$k['stPatronym'].'</p>
                                <p><span>Год рождения студента:</span> '.$k['stBirthyear'].'</p>
                                <p><span>Телефон студента:</span> +7'.$k['stNumber'].'</p>');
                                while ($i = mysqli_fetch_array($res)) {
                                    print('
                                    <p><span>Выбранные курсы:</span>  ['.$i['code'] .'] '.$i['name'].'</p>');
                                }
                                print('
                                <p><span>Имя родителя:</span> '.$k['prName'].'</p>
                                <p><span>Фамилия родителя:</span> '.$k['prSurname'].'</p>
                                <p><span>Отчетство родителя:</span> '.$k['prPatronym'].'</p>
                                <p><span>Номер родителя:</span> +7'.$k['prNumber'].'</p>
                                <p><span>Email родителя:</span> '.$k['prEmail'].'</p>
                            </div>
                            <div class = "application-buttons">
                                <a href="index.php?page=applications&action=update_application_form&ID='.$k['ID'].'">
                                    Edit
                                </a>
                                <a href="index.php?page=applications&action=delete_application&ID='.$k['ID'].'">
                                    Delete
                                </a>
                            </div>
                        </div>');
					if($admin && !empty($_GET['action']) && ($_GET['action']=='update_application_form' && !empty($_GET['ID'])&&($_GET['ID']==$k['ID']))) {
				         print(' 
				         		<div class="update-application-form-container">
                                 <h3 class="text">Изменение заявки:</h3>
									<form action="index.php?page=applications&action=update_application&ID='.$k['ID'].'" method="POST"> 
                                    <div class = "application-update-student-container">
                                    <h4>Данные студента:</h4>
                                        <label for="stName">Имя студента:</label>
										<input type="text" name="stName" value="'.$k['stName'].'"/>
                                        <label for="stSurname">Фамилия студента:</label>
										<input type="text" name="stSurname" value="'.$k['stSurname'].'"/>
                                        <label for="stPatronym">Отчество студента:</label>
										<input type="text" name="stPatronym" value="'.$k['stPatronym'].'">
                                        <label for="stBirthyear">Год рождения:</label>
                                        <input type="text" name="stBirthyear" value="'.$k['stBirthyear'].'">
                                        <label for="stNumber">Номер студента:</label>
                                        <input type="text" name="stNumber" value="'.$k['stNumber'].'">
                                        <label for="chosen_courses">Выбранные курсы:</label>
                                        <select name="chosen_courses[]" multiple>');
                                            $sqltext = "SELECT id, code, name FROM Courses";
                                            $result = mysqli_query($db, $sqltext);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' .$row["code"] . '"> [' . $row["code"]. '] ' . $row["name"]. '
                                                </option>';
                                            }
                                    print('
                                        </select>
                                        </div>
                                        <div class = "application-update-parent-container">
                                        <h4>Данные родителя</h4>
                                        <label for="prName">Имя родителя</label>
                                        <input type="text" name="prName" value="'.$k['prName'].'">
                                        <label for="prSurname">Фамилия родителя</label>
                                        <input type="text" name="prSurname" value="'.$k['prSurname'].'">
                                        <label for="prPatronym">Отчество родителя</label>
                                        <input type="text" name="prPatronym" value="'.$k['prPatronym'].'">
                                        <label for="prNumber">Телефон родителя</label>
                                        <input type="number" name="prNumber" value="'.$k['prNumber'].'">
                                        <label for="prEmail">Email родителя</label>
                                        <input type="text" name="prEmail" value="'.$k['prEmail'].'">
                                        <label for="date">Дата подачи заявки:</label>
										<input type="text" name="date" value="'.$k['date'].'"/>
										<input type="hidden" name="ID" value="'.$k['ID'].'"/>
                                        </div>
                                        <input type="submit" value="Изменить"/>
				                	</form>
                                    
				                </div>');
				    }
				}
				print('
					</div>');
		}

        // Change application
		if($admin && !empty($_GET['action']) && 
        ($_GET['action']=='update_application' && !empty($_GET['ID']))) {
			$sqltext='update Applications set 
                    stName="'.$_POST['stName'].'", 
                    stSurname="'.$_POST['stSurname'].'", 
                    stPatronym="'.$_POST['stPatronym'].'",
                    stBirthyear="'.$_POST['stBirthyear'].'",
                    stNumber="'.$_POST['stNumber'].'",
                    prName="'.$_POST['prName'].'",
                    prSurname="'.$_POST['prSurname'].'",
                    prPatronym="'.$_POST['prPatronym'].'",
                    prNumber="'.$_POST['prNumber'].'",
                    prEmail="'.$_POST['prEmail'].'",
                    date="'.$_POST['date']
			         .'" where ID='.$_POST['ID'];
            mysqli_query($db,$sqltext);

            $sqltext = "DELETE FROM CoursesApplications WHERE applicationID = ".$_POST['ID'];
            mysqli_query($db, $sqltext);

            foreach ($_POST['chosen_courses'] as $courseCode) {
                $sqltext = "SELECT ID FROM Courses WHERE code = '{$courseCode}'";
                $result = mysqli_query($db, $sqltext);
                $row = mysqli_fetch_assoc($result);
                $courseID = $row['ID'];
    
                $sql = "INSERT INTO CoursesApplications (applicationID, courseID) VALUES ({$_POST['ID']}, {$courseID})";
                mysqli_query($db, $sql);
            }
			print('<br/><br/><p>Заявка изменена</p><br/>');
            
			
         }

        // Delete application
		if($admin && !empty($_GET['action']) 
        && ($_GET['action']=='delete_application') && !empty($_GET['ID'])) {
			$sqltext="DELETE FROM Applications WHERE ID=".$_GET['ID'];
			print('<p">Заявка удалена<br/></p>');
			mysqli_query($db,$sqltext); 
		}
?>