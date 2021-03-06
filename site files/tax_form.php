<?php

 require_once 'helpers/greek_to_uppercase.php';

 require_once 'login.php';
 $conn = new mysqli($cleardb_server,$cleardb_username,$cleardb_password,$cleardb_db);
 // Check Connection
 if ($conn->connect_error) die ($conn->connect_error);
 // Escape user inputs for security
 // Take arguments from POST method
 $app_id = $_GET["id"];
 // Select from users where username and password
 mysqli_query($conn, "SET NAMES 'utf8'");

 $query = "SELECT * FROM users WHERE id=$app_id";
 $res= $conn->query($query);
 $res->data_seek(0);
 $row = $res->fetch_assoc();
 $name = $row['firstname'];
 $surname = $row['lastname'];

 $app_date = date("d-m-Y");
 $completed = 1;
 mysqli_query($conn, "SET NAMES 'utf8'");
 $query="SELECT * FROM applications WHERE users_id=$app_id";
 $res= $conn->query($query);
 $anything_found = mysqli_num_rows($res);
 if($anything_found==0)
  $query = "INSERT INTO applications (completed, users_id, app_date) VALUES ('$completed', '$app_id', '$app_date')";
 $res= $conn->query($query);



  require('tfpdf/tfpdf.php');

  $pdf = new tFPDF();
  $pdf->AddPage();

  // Add a Unicode font (uses UTF-8)
  $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
  $pdf->SetFont('DejaVu','',24);
  $pdf->Write(8,'ΙΔΡΥΜΑ ΚΟΙΝΩΝΙΚΩΝ ΑΣΦΑΛΙΣΕΩΝ');
  $pdf->Ln(10);
  $pdf->Ln(10);
  $pdf->SetFont('DejaVu','',20);
  $pdf->Write(8,'Βεβαίωση για φορολογική χρήση');
  $pdf->Ln(10);
  $pdf->Ln(10);
  $pdf->Write(8,'Βεβαιώνουμε ότι ο / η  συνταξιούχος με όνομα: ');
  $pdf->Write(8,$name);
  $pdf->Write(8,' ');
  $pdf->Write(8,$surname);
  $pdf->Write(8,' λαμβάνει το ποσό των ');
  $pdf->Write(8,$row['money']);
  $pdf->Write(8, ' ευρώ κάθε μήνα.');
  $pdf->Ln(10);
  $pdf->Write(8, 'Μεικτό Εισόδημα ανά έτος: ');
  $pdf->Write(8, $row['money'] * 12);
  $pdf->Write(8, ' Ευρώ');
  $pdf->Output();
?>
