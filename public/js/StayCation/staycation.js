let stayCationId;
let formSubmit = $('.form-submit');
let popUp = $('#staycation-modal');
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
//add staycation //
$(document).on('click','.add-staycation-btn',function(){
    stayCationId = this.id;
    formSubmit.trigger('reset');
    formSubmit.find('#region, #province, #city, #barangay').val('').trigger('change');
    formSubmit.find('input, select, textarea').attr('disabled',false);
    popUp.find('.form-submit').removeAttr('id').attr('id','add-staycation-form');
    popUp.find('.modal-title').text('Add Resort');
});
$(document).on('submit','#add-staycation-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/staycations',
        'type' : 'POST',
        'data' : data,
        beforeSend: function (){
            formSubmit.find('input, select, textarea').attr('disabled',true);
        },success: function(response){
            if(response.success === true)
            {
                let table = $('#staycations-list').DataTable();
                table.ajax.reload(null, false);

                formSubmit.trigger('reset');
                formSubmit.find('#region, #province, #city, #barangay').val('').trigger('change');
                Toast.fire({
                    type: 'success',
                    title: response.message
                })
            }
            formSubmit.find('input, select, textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            formSubmit.find('input, select, textarea').attr('disabled',false);
        }
    });
    clear_errors('name','address_number','region','province','city');
});

//end add staycation//

//edit staycation//
$(document).on('click','.edit-staycation-btn',function(){
    stayCationId = this.id;
    $('.text-danger').remove();
    popUp.find('.form-submit').removeAttr('id').attr('id','edit-staycation-form');
    popUp.find('.modal-title').text('Edit Resort');
    popUp.modal('toggle');
    $.ajax({
        'url' : '/staycation-stored-info/'+stayCationId,
        'type' : 'GET',
        beforeSend: function(){

        },success: function(response){
            formSubmit.find('#name').val(response.stayCation.name);
            formSubmit.find('#address_number').val(response.stayCation.address.address_number);
            $("#region option[value='" + response.stayCation.address.region + "']").attr("selected","selected");
            formSubmit.find('#description').val(response.stayCation.description);

            $('#province').html('<option></option>');
            $('#city').html('<option></option>');
            $('#barangay').html('<option></option>');

            $.each(response.provinces,function(key, value){

                let selectThis = (value.province_code == response.stayCation.address.province) ? 'selected' : '';
                $('#province').append('<option value="'+value.province_code+'" '+selectThis+'>'+value.province_description+'</option>');
            });
            $.each(response.cities,function(key, value){

                let selectThis = (value.city_municipality_code == response.stayCation.address.city) ? 'selected' : '';
                $('#city').append('<option value="'+value.city_municipality_code+'" '+selectThis+'>'+value.city_municipality_description+'</option>');
            });

            $.each(response.barangays,function(key, value){

                let selectThis = (value.barangay_code == response.stayCation.address.barangay) ? 'selected' : '';
                $('#barangay').append('<option value="'+value.barangay_code+'" '+selectThis+'>'+value.barangay_description+'</option>');
            })

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#edit-staycation-form',function(f){
    f.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/staycations/'+stayCationId,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            formSubmit.find('input, select, textarea').attr('disabled',true);
        },success: function(response){
            // console.log(response);
            if(response.success === true)
            {
                Toast.fire({
                    type: 'success',
                    title: response.message
                });
                let table = $('#staycations-list').DataTable();
                table.ajax.reload(null, false);
            }
            else if(response.success === false)
            {
                Toast.fire({
                    type: 'warning',
                    title: response.message
                });
            }
            formSubmit.find('input, select, textarea').attr('disabled',false);
        },error: function (xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            formSubmit.find('input, select, textarea').attr('disabled',false);
        }
    });
});
// end edit staycation //

//delete staycation //
$(document).on('click','.delete-staycation-btn',function(){
    stayCationId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Staycation?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/staycations/'+stayCationId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#staycations-list').DataTable();
                        table.ajax.reload(null, false);
                        Swal.fire(
                            'Removed!',
                            response.message,
                            'success'
                        )
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
//end delete staycation
