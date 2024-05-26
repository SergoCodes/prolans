<?php
    $ages = [
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
        13 => '13',
        14 => '14+'
    ];
    $subjects = [
        'graphics' => '2D и 3D графика',
        'internet' => 'Интернет и сети',
        'programming' => 'Программирование',
        'all' => 'Все'
    ];

    // Category courses initialization
    $introductory_courses = [];
    $primary_courses = [];
    $specialized_courses = [];

    // Get current filter values
    $current_age = isset($_GET['age']) ? $_GET['age'] : null;
    $current_subject = isset($_GET['course_subject']) ? $_GET['course_subject'] : null;

    // Course filter
    if ($current_age) {
        $filter = intval($_GET['age']);
        $sql = "SELECT * FROM Courses WHERE age <= $filter";
    } else if ($current_subject && $current_subject != 'all') {
        $filter = $subjects[$_GET['course_subject']];
        $sql = "SELECT * FROM Courses WHERE subject = '$filter'";
    } else {
        $sql = "SELECT * FROM Courses";
    }
    $result = mysqli_query($db, $sql);
    
    // Request's result proccessing
    while ($row = mysqli_fetch_assoc($result)) {
        switch ($row['category']) {
            case 'Вводные': 
                $introductory_courses[] = $row;
                break;
            case 'Основные': 
                $primary_courses[] = $row;
                break;
            case 'Специализированные': 
                $specialized_courses[] = $row;
                break; 
            default:
                break;
        }
    }
?>

<!-- Show course filter -->
<div class = "banner courses-banner">
    <div class = "container">
            <h1>Выберите <span style = "color: var(--accent-color)">курс</span> </h1>
            <p>Используйте фильтры ниже, чтобы подобрать идеальный курс для вас.</p>
        <div>
            <div class = "courses-filter-container">
                <h4 class = "regular">Выберите возраст:</h4>
                <ul>
                <?php
                foreach ($ages as $age => $label) { 
                    $selected = $age == $current_age ? 'selected' : '';
                    echo '
                        <li>
                            <a href = "index.php?page=courses&age='. $age .'">
                                <button class = "button filter-button ' . $selected . '"onclick = "selectButton(this)"  name = "age" value = "' .$age . '">' . $label . '
                                </button>
                            </a>
                        </li>
                    ';
                }
                ?>
                </ul>
            </div>
            <div class = "courses-filter-container">
                <h4 class = "regular">Выберите направление:</h4>
                <ul>
                <?php
                foreach ($subjects as $subject => $label) { 
                    $selected = $subject == $current_subject ? 'selected' : '';
                    echo '
                        <li>
                            <a href = "index.php?page=courses&course_subject='. $subject .'">
                                <button class = "button filter-button ' . $selected . '" onclick = "selectButton(this)" name="subject" value="' . $subject . '">' . $label . '
                                </button>
                            </a>
                        </li>
                    ';
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="courses-banner-img-wrapper">
        <img src="./images/bannerImage_Courses.svg">
    </div>
    
</div>

<!-- Showing courses and categories -->
<div class="courses-container">
<?php
    if (!empty($introductory_courses)) {
        echo '
        <h3 class = "courses-category">Вводные курсы</h3>
        <div class = "courses-wrap">
        ';
        foreach ($introductory_courses as $course) {
            print('
            <div class="course-item" style = "background-image: url(\''. $course['blockBG'] .'\')">
                <div class="course-item-container">
                    <div class = "course-item-p">
                        <p>' . $course['code'] . '</p>
                    </div>
                    <img src="' . $course['blockImage'] . '" alt="Изображение курса">
                </div>
                <h4>' . $course['name'] . '</h4>
            </div>'
            );
        }
        echo '
        </div>
        ';
    }

    if (!empty($primary_courses)) {
        echo '
        <h3 class = "courses-category">Основные курсы</h3>
        <div class = "courses-wrap">
        ';
        foreach ($primary_courses as $course) {
            print('
                <div class="course-item" style = "background-image: url(\''. $course['blockBG'] .'\')">
                    <div class="course-item-container">
                        <div class = "course-item-p">
                            <p>' . $course['code'] . '</p>
                        </div>
                        <img src="' . $course['blockImage'] . '" alt="Изображение курса">
                    </div>
                    <h4>' . $course['name'] . '</h4>
                </div>'
            );
        }
        echo '
        </div>
        ';
    }

    if (!empty($specialized_courses)) {
        echo '
        <h3 class = "courses-category">Специализированные курсы</h3>
        <div class = "courses-wrap">
        ';
        foreach ($specialized_courses as $course) {
            print('
                <div class="course-item" style = "background-image: url(\''. $course['blockBG'] .'\')">
                    <div class = "course-item-container">
                    <div class = "course-item-p">
                        <p>' . $course['code'] . '</p>
                    </div>
                        <img src="' . $course['blockImage'] . '" alt="Изображение курса">
                    </div>
                    <h4>' . $course['name'] . '</h4>
                </div>'
            );
        }
        echo '
        </div>
        ';
    }
?>
