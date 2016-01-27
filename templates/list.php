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
<script>
var surname;
var firstname;
var token = "<?=$token?>";
$(document).ready(function()
{
    $("#add").click(function()
    {
        firstname = $(".firstname_add").val();
        surname = $(".surname_add").val();

        if (!firstname && !surname)
        {
            alert("Please add atleast Firstname!");
            return false;
        }

        regex = /[a-zA-Z]+/g;
        match = regex.test(firstname);

        regex = /[a-zA-Z]+/g;
        match2 = regex.test(surname);

        if (!match || !match2)
        {
            //alert("Name can be only letters");
            //return false;
        }

        $("#form").submit();
    })

    $(".delete_button").click(function()
    {
        id = $(this).attr("data-id");
        if (id)
        {  
            var q = confirm("Are you sure?");

            if (q)
            {
                $.post("/delete",{id:id,token:token},function(data)
                {   
                    if (data.res == 1)
                    {
                        showMessage("Record deleted successfully","green");
                        $("#tr"+id).remove();
                    } else {
                        showMessage("Error while removing record from file","red");
                    }
                },'json')
            }
        } else {
            showMessage("Empty value","red");
        }
    })

    $(".update_button").click(function()
    {
        id = $(this).attr("data-id");
        firstname = $(".firstname"+id).val();
        surname = $(".surname"+id).val();

        if (id && ((firstname != '') || (surname != '')))
        {  
            $.post("/update",{id:id,token:token,firstname:firstname,surname:surname},function(data)
            {   
                if (data.res == 1)
                {
                    showMessage("Record updated successfully","green");
                } else {
                    showMessage(data.res,"red");
                }
            },'json')
        } else {
            showMessage("Empty values","red");
        }
    })
})

function showMessage(message,color)
{
    $(".ajax_result").hide().html(message).css({"color":color}).show();
}
</script>   
</body>
</html>