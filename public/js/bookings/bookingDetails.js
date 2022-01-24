
function bookingDetails(bookingsId, element)
{
    let details;
    $.ajax({
        url : '/booking-details/'+bookingsId,
        type : 'GET',
        async: false,
        beforeSend: function(){
            element.find('.modal-content').prepend('<div class="overlay">\n' +
                '                <i class="fas fa-2x fa-sync fa-spin"></i>\n' +
                '            </div>');
        },success: function(response){
            element.find('.modal-body').html(content(response));
            element.find('.overlay').remove();
            console.log(response);
        },error: function(xhr, status, error){
            console.log(xhr)
        }
    });
    return details;
}

function content(data)
{
    const remarks = data.remarks !== null ? data.remarks : "";
    return  '<strong><i class="fas fa-box-open"></i> Package Name</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="package">'+data.title+'</p>\n' +
        '                    <hr>\n' +
        '                    <strong><i class="far fa-calendar-alt"></i> Date</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="date">'+moment(data.start).format("MMM-DD-YYYY hh:mm a")+' to '+moment(data.end).format("MMM-DD-YYYY hh:mm a")+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '                    <strong><i class="fas fa-user-tie"></i> Guest</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="guest">'+data.customer.firstname+' '+data.customer.lastname+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '                    <strong><i class="fas fa-users"></i> Pax</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="pax">'+data.pax+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '                    <strong><i class="fas fa-money-bill-wave"></i> Total Amount</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="amount">'+parseFloat(data.total_amount).toLocaleString()+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '\n' +
        '                    <strong><i class="fas fa-user-plus"></i> Booked By</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="booked_by">'+data.user.username+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '                    <strong><i class="fas fa-calendar-day"></i> Date Booked</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="date_booked">'+moment(data.created_at).format('MMM-DD-YYYY hh:mm a')+'</p>\n' +
        '\n' +
        '                    <hr>\n' +
        '\n' +
        '                    <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>\n' +
        '\n' +
        '                    <p class="text-muted" id="remarks">'+ remarks+'</p>';
}
