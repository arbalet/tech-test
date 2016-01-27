var surname;
var firstname;
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