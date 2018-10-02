<html> 
<head> 
    <title>Skarbnik-ustawienia</title>
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
    <a href="expenses.php">Wydatki klasowe</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="addOnceEvent.php">Dodaj zbiórkę</a>
  <a href="settings.php" class="active">Ustawienia</a>
    <a href="../logout.php"> Wyloguj się</a>
</div>

<div class="lewa_strona">
    <h1> Ustawienia </h1>
    <h2> Informacja o klasie </h2>
    <h4> Lista uczniów </h4>
    
    <div id="students_list"></div>
    
    <h4> Dane skarbnika </h4>

    <div id="treasuer_data"></div>

    <br>
    <h2> Zmien hasło </h2>
    
    <form action="../treasurer_helper.php" method="post" id="form1">
    <table>
        <tr><td>Nowe hasło: </td><td><input type="password" name="newPassword" /></td></tr> 
        <tr><td colspan="2"><input type="submit"  name="changePassword" value="Zatwierdz"/></td></tr>

    <table>
    </form>
    
    <br>
    <h2> Zmień kwotę miesięcznej składki klasowej </h2>
    <form action="../treasurer_helper.php" method="post" id="form1">
    <table>
        <tr><td>Nowe miesięczna składka: </td><td><input type="monthly_fee" name="newMonthlyFee" /></td></tr> 
        <tr><td colspan="2"><input type="submit"  name="changeMonthlyFee" value="Zatwierdz"/></td></tr>

    <table>
    </form>
</div>


<!--MODAL CHANGE PARENR MAIL -->
<div id="changeParMailModal" class="modal fade">
 <div class="modal-dialog">
  <form action="../treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">

    <h2>ZMIANA MAILA RODZICA</h2>
    <h3>Podaj nowego maila rodzica</h3>
        Email: <input type="text" name="newParentMail"/>
        <input type="submit" name="changeParentMail" class="btn_commitChange" value="Zatwierdz"/>

   </div>
  </form>
 </div>
</div>
</body>
</html>


<script>
$(document).ready(function(){
function fetch_students_list()
    {
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call:'students_list'},
            success:function(data){
                $('#students_list').html(data);
            }
        });
        
    }
    fetch_students_list();  
    
    
    function fetch_treasuer_data()
    {
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call:'treasuer_data'},
            success:function(data){
                $('#treasuer_data').html(data);
            }
        });
        
    }
    fetch_treasuer_data(); 

//delete
    $(document).on('click','.btn_deleteStudent',function(){
    var id=$(this).data("id3");
    if(confirm("Czy jestes pewny, ze chcesz usunąć ucznia z tej klasy?"))  
           {
    $.ajax({
        url:"../treasurer_helper.php",
        method:"POST",
        data:{function2call: 'deleteStudent', id:id},
        dataType:"text",
        success:function(data){
            alert(data);
            fetch_students_list();
            
                     }  
                });  
           }  
      });
      
      //save which parent mail i want to change
      $(document).on('click','.btn_pMailChange',function(){
    var id=$(this).data("id3");
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call: 'btn_pMailChange', id:id},
            dataType:"text",
                success:function(data){
                     }                          
                });
    
          
      });
    
      
      
 }); 
  

</script>