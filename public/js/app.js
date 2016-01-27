/**
 * Created by Clutz on 16/01/2016.
 */
/** Add **/
$(function() {
    $(".add").click(function() {
        var firstname = $(this).closest('tr').find('.firstname');
        var surname = $(this).closest('tr').find('.surname');
        var csrf = $("#csrf").val();
        $.ajax({
            url: "/add",
            method: "post",
            data: {
                firstname:  firstname.val(),
                surname:    surname.val(),
                csrf:       csrf
            },
            success: function (data) {
                var response = $.parseJSON(data);
                var newRow = $(".hid").clone();
                newRow.find('.firstname').val(response.firstname);
                newRow.find('.surname').val(response.surname);
                newRow.find('.id').val(response.id);
                newRow.removeClass('hid');
                $("tbody").append(newRow);

                updateObserver();
                deleteObserver();

                firstname.val('');
                surname.val('');
            },
            complete: function (xhr) {
                if (xhr.status != 200) {
                    alert('Failed to add row');
                }
            }
        });
    });
    updateObserver();
    deleteObserver();
});
function updateObserver() {
    $(".update").click(function () {
        var id = $(this).closest('tr').find('.id');
        var firstname = $(this).closest('tr').find('.firstname');
        var surname = $(this).closest('tr').find('.surname');
        var csrf = $("#csrf").val();
        $.ajax({
            url: "/update",
            method: "post",
            data: {
                id: id.val(),
                firstname: firstname.val(),
                surname: surname.val(),
                csrf: csrf
            },
            success: function (data) {
                var response = $.parseJSON(data);
                id.val(response.id);
                firstname.val(response.firstname);
                surname.val(response.surname);
            },
            complete: function (xhr) {
                if (xhr.status != 200) {
                    alert('Failed to update row');
                }
            }
        });
    });
}
function deleteObserver() {
    $(".delete").click(function () {
        var row = $(this).closest('tr');
        var id = $(this).closest('tr').find('.id');
        var csrf = $("#csrf").val();
        $.ajax({
            url: "/delete",
            method: "post",
            data: {
                id: id.val(),
                csrf: csrf
            },
            success: function (data) {
                var response = $.parseJSON(data);
                if (response.message == 'success') {
                    row.empty();
                }
            },
            complete: function (xhr) {
                if (xhr.status != 200) {
                    alert('Failed to delete row');
                }
            }
        });
    });
}