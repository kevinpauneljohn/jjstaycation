
function bookingDetails(bookingsId)
{
    let details;
    $.ajax({
        url : '/booking-details/'+bookingsId,
        type : 'GET',
        async: false,
        beforeSend: function(){

        },success: function(response){
              details = response
        },error: function(xhr, status, error){

        }
    });
    return details;
}
