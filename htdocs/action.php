<?php
  session_start();
  $conn = mysqli_connect('mysql', 'root', 'rootpassword', 'wedding');
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  /*-----admin login-----*/
  if (isset($_POST['admin'])) {
    $name = $_POST['name'];
    $psw = $_POST['psw'];
    $sql = "SELECT * FROM admin WHERE name=? AND psw=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $psw);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['name'] = $name;
        header('location:../dasboard.php');
    } else {
        echo "<h2>Invalid Admin credentials</h2>";
    }
    mysqli_stmt_close($stmt);
  }

  /*--------user registration------*/
  if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $pno = $_POST['pno'];
    $add = $_POST['add'];
    $psw = $_POST['psw'];
    $repsw = $_POST['repsw'];

    if ($psw == $repsw) {
        $sql = "SELECT * FROM u_info WHERE uname=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $uname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<h2>USERNAME ALREADY EXISTS, PLEASE ENTER A VALID USERNAME</h2>";
        } else {
            $sql = "INSERT INTO u_info (name, uname, email, pno, adds, psw) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $uname, $email, $pno, $add, $psw);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['uid'] = mysqli_insert_id($conn);
                $_SESSION['uname'] = $uname;
                $_SESSION['urname'] = $name;
                header('location:login.php');
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<h2>Password does not match</h2>";
    }
  }

  /*--------------user login------*/
  if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $psw = $_POST['psw'];
    $sql = "SELECT * FROM u_info WHERE uname=? AND psw=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $psw);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $_SESSION['uname'] = $name;
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['urname'] = $row['name'];
        header('location:../profile.php');
    } else {
        echo "<h2>Invalid credentials</h2>";
    }
    mysqli_stmt_close($stmt);
  }

  /*------------------wedding registration-------*/
  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $dname = $_POST['dname'];
    $dlname = $_POST['dlname'];
    $date = $_POST['date'];
    $pno = $_POST['pno'];
    
    $sql = "SELECT * FROM registration WHERE name=? AND dlname=? AND dname=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $dlname, $dname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Already registered, please check dashboard</h2>";
    } else {
        $sql = "INSERT INTO registration (name, dname, dlname, wdate, pno) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $dname, $dlname, $date, $pno);
        if (mysqli_stmt_execute($stmt)) {
            header('location:../profile.php');
        }
    }
    mysqli_stmt_close($stmt);
  }

  /*------------------service addition--------*/
  function addService($table, $name, $price, $desc, $conn) {
      $sql = "SELECT * FROM $table WHERE name=?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "s", $name);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) > 0) {
          echo "<h2>Already present in database</h2>";
      } else {
          $sql = "INSERT INTO $table (name, price, descr) VALUES (?, ?, ?)";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "sss", $name, $price, $desc);
          if (mysqli_stmt_execute($stmt)) {
              header('location:../dasboard.php');
          }
      }
      mysqli_stmt_close($stmt);
  }

  if (isset($_POST['venue'])) {
      addService('venue', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  if (isset($_POST['music'])) {
      addService('music', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  if (isset($_POST['dect'])) {
      addService('decoration', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  if (isset($_POST['cat'])) {
      addService('catering', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  if (isset($_POST['theme'])) {
      addService('theme', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  if (isset($_POST['photo'])) {
      addService('photoshop', $_POST['vname'], $_POST['price'], $_POST['desc'], $conn);
  }

  /*--------------------searching operation----------------------*/

  if (isset($_POST['search'])) {
    $bid = $_POST['bid'];
    $sid = $_POST['sid'];
    $date = $_POST['date'];
    
    // Add the switch cases here to fetch and display the result
  }
?>
