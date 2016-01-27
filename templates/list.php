<!DOCTYPE html>
<html>
  <head>
      <title>SkyBet - Tech Test</title>
      <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    </head>
<body>
<div class="ajax_result" style="height:30px;">
    
</div>
<form method="post" id="form" action="/add">
    <input type="hidden" name="token" value="<?=$token?>">
    <table>
        <tr>
            <th>First name</th>
            <th>Last name</th>
        </tr>
        <?php if (!empty($persons))
        {
            foreach ($persons as $id=>$p)
            {
                ?> 
                  <tr id='tr<?=$id?>'>
                    <td><input type="text" name="people[firstname]" value="<?=$p['firstname']?>" class='firstname<?=$id?>' /></td>
                    <td><input type="text" name="people[surname]" value="<?=$p['surname']?>" class='surname<?=$id?>'/></td>
                    <td><input type="button" class='update_button' data-id="<?=$id?>" value="Save changes"><input type="button" class='delete_button' data-id="<?=$id?>" value="Delete"></td>
                </tr>
            <?php
            }  
        }
        ?>
        <tr>
            <td><input type="text" name="people[][firstname]" value="" class='firstname_add' /></td>
            <td><input type="text" name="people[][surname]" value="" class='surname_add' /></td>
            <td><input type="submit" value=" Add " id="add" name="add">
        </tr>
    </table>
</form>
<script type="text/javascript" src="/js/main.js"></script>
<script>
var token = "<?=$token?>"; //also we can get the token by the hidden field too.
</script>  
</body>
</html> 