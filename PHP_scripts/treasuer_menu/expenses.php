<html> 
<head> 
    <title>Skarbnik-wydatki</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
    
    
    <script src="../js/jquery-2.2.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  

    <style>
/* Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}
</style>
    
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}

?>
<body>

<div class="menu">
    <a href="../menu_treasurer.php" >Strona główna</a>
    <a href="../menu_parent.php">Moje dzieci</a>
    <a href="expenses.php" class="active">Wydatki klasowe</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="addOnceEvent.php">Dodaj zbiórkę</a>
  <a href="settings.php" >Ustawienia</a>
    <a href="../logout.php"> Wyloguj się</a>
</div>

<div class="lewa_strona">
    <h1> Wydatki klasowe </h1>
    <h2> Lista wydatków </h2>
        <div id="expenses_list"></div>
        
        <br>
        <button type="button" data-toggle="modal" data-target="#addExpense" class="btn_deleteEvent">Dodaj wydatek</button>
</div>


<!--MODAL ADD EXPENSE -->
<div id="addExpense" class="modal fade">
 <div class="modal-dialog">
  <form action="../treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">

    <h2>DODAJ WYDATEK KLASOWY</h2>
    <h3>Podaj informacje o wydatku klasowym</h3>
    <table>
        <tr><td>Nazwa: </td><td> <input type="text" name="expenseName"/></td></tr>
        <tr><td>Cena: </td><td> <input type="text" name="expensePrice"/></td></tr>
    </table>
    <input type="submit" name="addExpense" class="btn_addExpense" value="Zatwierdz"/>
   </div>
  </form>
 </div>
</div>



</body>
</html>



<script>
$(document).ready(function(){
    
    function fetch_expenses_list()
    {
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call:'fetch_expenses_list'},
            success:function(data){
                $('#expenses_list').html(data);
            }
        });
        
    }
    fetch_expenses_list();
        
 }); 
  </script>