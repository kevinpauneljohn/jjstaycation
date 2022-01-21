let url = window.location.href;
let assignModal = $('#assign-staycation-modal');
let staycationId;
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
$(document).on('click','.assign-user-btn',function(){
    $('.staycation-details').find('table').remove();
    assignModal.find('.form-submit').removeAttr('id').attr('id','assign-user-form');
    assignModal.modal('toggle');
});

$(document).on('change','#staycations',function(){
    let selected_staycations = $('#staycations option:selected')
        .toArray().map(item => item.value);

    $.ajax({
        'url' : '/get-selected-staycations',
        'type' : 'GET',
        'data' : {
            'id' : selected_staycations
        },
        beforeSend: function(){
            $('.staycation-details').find('table').remove();
        },success: function(response){
            console.log(response);
            $('.staycation-details').html('<table class="table table-bordered"><tr><td>Name</td><td>Address</td></tr></table>');
            $.each(response,function(key, value){
                $('.staycation-details').find('table').append('<tr><td class="text-blue">'+value.name+'</td><td>'+value.full_address+'</td></tr>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#assign-user-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/assign-users-to-staycation',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#assign-user-form').find('#staycations').attr('disabled',true);
            $('#assign-user-form').find('.submit-assign-btn').attr('disabled',true).val('Saving...');
        },success: function(response){
            console.log(response);
            if(response.success === true)
            {
                let table = $('#assigned-staycation-list').DataTable();
                table.ajax.reload(null, false);
            }
            $('#staycations').load(url+'#staycations option');
            errorDisplay(response);

            $('#assign-user-form').find('#staycations').attr('disabled',false);
            $('#assign-user-form').find('.submit-assign-btn').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
            $('#assign-user-form').find('#staycations').attr('disabled',false);
            $('#assign-user-form').find('.submit-assign-btn').attr('disabled',false).val('Save');
        }
    });
    clear_errors('staycations');
});

//delete assigned staycation //
$(document).on('click','.delete-assigned-staycation-btn',function(){
    staycationId = this.id;
    let userId = $('input[name=user]').val();

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Package?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/remove-assigned-staycations/'+staycationId+'/'+userId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#assigned-staycation-list').DataTable();

                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }
                    $('#staycations').load(url+'#staycations option');
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});

//end delete assigned staycation//
