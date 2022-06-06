<?php
session_start();
if (!isset($_SESSION['sessionid'])) {
    echo "<script>alert('Session not available. Please login');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

include 'dbconnect.php';
$sqlcourses = "SELECT * FROM tbl_subjects";
$stmt = $conn->prepare($sqlcourses);
$stmt->execute();
$rows = $stmt ->fetchAll();

$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = 0;
}

$stmt = $conn->prepare($sqlcourses);
$stmt->execute();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlcourses = $sqlcourses . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqlcourses);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MY Tutor</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Montserrat:wght@600&display=swap"
      rel="stylesheet"
    />
    <link href="../css/style.css" rel="stylesheet"/>
  </head>

    <script>
    function hamburgerMenu() {
      var x = document.getElementById("idMenuBar");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
      } else {
        x.className = x.className.replace(" w3-show", "");
      }
    }
  </script>

  <body>
    <!-- Top navigation bar -->
    <div class="w3-bar w3-black w3-padding-16" id="navBar">
      <a href="homepage.php" class="w3-bar-item w3-button w3-wide w3-">
        <b>MY Tutor</b></a
      >
      <a href="courses.php" class="w3-bar-item w3-button w3-hide-small">Courses</a>
      <a href="tutors.php" class="w3-bar-item w3-button w3-hide-small"
        >Tutors</a
      >
      <a href="#subscription" class="w3-bar-item w3-button w3-hide-small">Subscription</a>
      <a href="#profile" class="w3-bar-item w3-button w3-hide-small">Profile</a>
      <!-- Right-sided navbar links -->
      <div class="w3-right">
        <a href="" class="w3-bar-item w3-button w3-hide-small"
          >LOGOUT</a
        >
      </div>

      <a
        href="javascript:void(0)"
        class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium"
        onclick="hamburgerMenu()"
      >
        <i class="fa fa-bars"></i>
      </a>
    </div>

    <!-- Hamburger menu -->
    <div
      id="idMenuBar"
      class="w3-bar-block w3-card w3-white w3-hide w3-hide-large w3-hide-medium"
    >
      <a href="courses.php" class="w3-bar-item w3-button w3-center">Courses </a>
      <a href="tutors.php" class="w3-bar-item w3-button w3-center">Tutors</a>
      <a href="#subscription" class="w3-bar-item w3-button w3-center">Subscription</a>
      <a href="#profile" class="w3-bar-item w3-button w3-center">Profile</a>
    </div>

    <div class ="w3-header w3-center w3-margin-top w3-padding w3-animate-opacity">
        <img src='../images/header_course.png' alt='' style='width:100%;'>
        </div>

    <div class="w3-content" style="max-width: 1200px">
    <div class="w3-center" >
    <h3>COURSES</h3>
      <div class="w3-grid-template w3-padding w3-animate-bottom">  

        <?php
        foreach ($rows as $courses) {
            $subid = $courses['subject_id'];
            $subname = $courses['subject_name'];
            $subdesc = $courses['subject_description'];
            $subprice = number_format((float)$courses['subject_price'], 2, '.', ''); // $products['product_price'];
            $tutorid = $courses['tutor_id'];
            $subsession = $courses['subject_sessions'];
            $subrating = $courses['subject_rating'];

            echo <<<END
              <div class='w3-card w3-container w3-padding w3-round-xlarge w3-hover-shadow'>
              <img src='../assets/courses/$subid.png' alt='' class='fitImg' style='width:100%;'>
              <div class='w3-content' style='height: 90px'>
              <h5>$subname</h5>
              </div>
              <p>by <img src='../assets/tutors/$tutorid.jpg' alt='' class='roundedImage'></p>
              <h6><i class='fa fa-star' style='font-size:20px'></i>  $subrating / 5.0</h6> 
              <p class='text'>$subdesc</p>
              <p>
              <h3>RM $subprice</h3>
              <h6 >($subsession sessions)</h><br><br>

              <button onclick="document.getElementById($subid).style.display='block'" class="w3-button w3-black">MORE DETAILS</button>
              <div id=$subid class="w3-modal">
                <div class="w3-modal-content w3-animate-bottom w3-card-4">
                  <header class="w3-container w3-black"> 
                    <span onclick="document.getElementById($subid).style.display='none'" 
                    class="w3-button w3-display-topright">&times;</span>
                    <h2>$subname</h2>
                  </header>
                  <div class="w3-container">
                  <img src='../assets/courses/$subid.png' alt='' class='fitImg' style='width:100%;'>

                  <p>by <img src='../assets/tutors/$tutorid.jpg' alt='' class='roundedImage'></p>
                  <h6><i class='fa fa-star' style='font-size:20px'></i>  $subrating / 5.0</h6> 
                  <p>$subdesc</p>
                  <h3>RM $subprice</h3>
                  <h6 >($subsession sessions)</h><br>
                  </div>
                </div>
              </div>

              </p>
              </div>
            END;
        }
        ?>

    </div>
      </div>
      </div>

      <?php
    $num = 1;
    if ($pageno == 1) {
        $num = 1;
    } else if ($pageno == 2) {
        $num = ($num) + 10;
    } else {
        $num = $pageno * 10 - 9;
    }
    echo "<div class='w3-container w3-row w3-padding-64'>";
    echo "<center>";
    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<a href = "courses.php?pageno=' . $page . '" style=
            "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
    }
    echo " ( " . $pageno . " )";
    echo "</center>";
    echo "</div>";
    ?>
    
      <!-- footer -->
  <footer class="w3-row-padding w3-padding-16 w3-center w3-black">
    <div class="w3-small">
      <a href="courses.php" class="w3-bar-item w3-button">Courses</a>
      <a href="tutors.php" class="w3-bar-item w3-button">Tutors</a>
      <a href="#subscription" class="w3-bar-item w3-button">Subscription</a>
      <a href="#profile" class="w3-bar-item w3-button">Profile</a>
    </div>

    <h6>© 2022 MY Tutor</h6>
  </footer>

  </body>
</html>
