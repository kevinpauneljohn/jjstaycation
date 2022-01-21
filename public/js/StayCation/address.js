$(document).on('change','#region',function(){
    let region = $(this).val();

    getProvince(region);
});

function getProvince(regionCode)
{
    $('#province').html('<option></option>');
    $('#city').html('<option></option>');
    $('#barangay').html('<option></option>');

    $.ajax({
        'url' : '/provinces/'+regionCode,
        'type' : 'GET',
        beforeSend: function(){
            $('form').find('input, select, textarea').attr('disabled',true);
        },success: function(response){
            $.each(response, function(key, value){
                $('#province').append('<option value="'+value.province_code+'">'+value.province_description+'</option>');
            });
            $('form').find('input, select, textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            // console.log(xhr);
        }
    });
}

$(document).on('change','#province',function(){
    let province = $(this).val();

    getCity(province);
});

function getCity(provinceCode)
{
    $('#city').html('<option></option>');
    $('#barangay').html('<option></option>');

    $.ajax({
        'url' : '/cities/'+provinceCode,
        'type' : 'GET',
        beforeSend: function(){
            $('form').find('input, select, textarea').attr('disabled',true);
        },success: function(response){
            $.each(response, function(key, value){
                $('#city').append('<option value="'+value.city_municipality_code+'">'+value.city_municipality_description+'</option>');
            });
            $('form').find('input, select, textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            // console.log(xhr);
        }
    });
}

$(document).on('change','#city',function(){
    let city = $(this).val();

    getBarangay(city);
});

function getBarangay(cityCode)
{
    $('#barangay').html('<option></option>');

    $.ajax({
        'url' : '/barangays/'+cityCode,
        'type' : 'GET',
        beforeSend: function(){
            $('form').find('input, select, textarea').attr('disabled',true);
        },success: function(response){
            $.each(response, function(key, value){
                $('#barangay').append('<option value="'+value.barangay_code+'">'+value.barangay_description+'</option>');
            });
            $('form').find('input, select, textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            // console.log(xhr);
        }
    });
}
