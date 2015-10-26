<!--ХИДЕР-->
<?php include 'application/views/partials/header.php'; ?>

<h1>ADMIN PANNEL</h1>

<div id=user_list >
<p style='padding-left: 55px'>=User List=</p>
   <table border="1" style='margin: 20px;'>
    <th> Login </th><th>Passwd</th>
   
   <?php
        if (!empty($data['user_list'])) 
        {
            foreach ($data['user_list'] as $value)
            {
                echo "<tr><td>".$value['name']." </td><td>".$value['hash']."</td></tr>";  
            }
        }
       
   ?>
    </table>
</div>

<div id=admin_form>
   <div style='border: 1px solid #696969; margin: 15px; padding: 0 10px;'> 
   <span style='border: 1px solid #696969; background-color: #333; position: relative; top: -10px; left: 10px; padding: 0 5px;'>Создать нового пользователя</span>
    <form action="" method=POST name=new_usr_form>
     <table>
      <tr><td align=left>Логин: </td><td align=right><input type=text name=new_usr value=""></td></tr>
      <tr><td align=left>Пароль:</td><td align=right><input type=text name=new_pass value=""></td></tr>
     </table> 
     <br>
      <input type=submit value=Создать name=btnadd>
    </form>
   </div>
   <div style='border: 1px solid #696969; margin: 15px; padding: 0 10px;'> 
   <span style='border: 1px solid #696969; background-color: #333; position: relative; top: -10px; left: 10px; padding: 0 5px;'>Удалить пользователя</span>
    <form action="" method=POST>
      <table>
      <tr><td align=left>Логин: </td><td align=right><input type=text name=del_usr value=""></td></tr>
      </table>
      <br>
      <input type=submit value=Удалить name=btndel>
    </form>
   </div> 
</div>

<script type="text/javascript">
        
    function validate_form()
    {
    valid = false;    
    if ( document.new_usr_form.new_usr.value == "" )
        {
                
                valid = true;
                return valid;
        }
    }
    function ref()
    {
       if (validate_form())
       {
        
        location.reload(true);
        } 
    }
    //setTimeout("ref()",5000)
</script> 