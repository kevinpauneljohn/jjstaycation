let dateStart;
let dateEnd;
let bookingModal = $('#booking-modal');
numberFormatter('#total_amount');
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});



// BS-Stepper Init
document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
});

$('#package').select2({
    allowClear: true,
    placeholder: 'Select Package'
});

$('#status').select2({
    allowClear: true,
    placeholder: 'Select Status'
});

$('#occasion').select2({
    allowClear: true,
    placeholder: 'Select Occasion'
});

function separator(numb) {
    var str = numb.toString().split(".");
    str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return str.join(".");
}

function customBookingButton()
{
    dateStart = $('#preferred_date').data('daterangepicker').startDate;

}

$(document).on('change','#package',function(){
    let data = this.value;
    $('.package-details').html('');
    $('#total_amount, #pax').val('');
    if(data === "custom")
    {
        // alert('custom');
    }else{
        dateStart = $('#preferred_date').data('daterangepicker').startDate;
        $.ajax({
            'url' : '/package-details/'+data,
            'type' : 'GET',
            beforeSend: function(){
            },success: function(response){

                let timeIn = moment(response.time_in,"H:mm").format("hh:mm a");
                let timeOut = moment(response.time_out,"H:mm").format("hh:mm a") ;
                let remarks = response.remarks !== null ? response.remarks : '';
                // let amount = parseInt(response.amount);
                let amount = separator(response.amount);


                let newDateStart = moment(dateStart.format('MM-DD-YYYY')+' '+timeIn, 'MM-DD-YYYY hh:mm a').format('MM-DD-YYYY hh:mm a');
                let newDateEnd = moment(dateStart.format('MM-DD-YYYY')+' '+timeOut, 'MM-DD-YYYY hh:mm a').add(parseInt(response.days) - 1,'days').format('MM-DD-YYYY hh:mm a');

                $('#preferred_date').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    startDate: newDateStart,
                    endDate: newDateEnd,
                    locale: {
                        format: 'MM/DD/YYYY hh:mm A'
                    },
                    isInvalidDate: function (date){
                        return blocked_dates.reduce(function(bool, range) {
                            return bool || (date >= range.start && date <= range.end);
                        }, false);
                    }
                });

                $('#total_amount').val(separator(response.amount));
                $('#pax').val(response.pax);
                $('.package-details').html('<table class="table table-bordered" id="display-package-info">' +
                    '<tr><td>Days</td><td class="text-blue">'+response.days+'</td></tr>' +
                    '<tr><td>Amount</td><td class="text-blue">'+amount+'</td></tr>' +
                    '<tr><td>Number Of Persons</td><td class="text-blue">'+response.pax+'</td></tr>' +
                    '<tr><td>Time In / Out</td><td><span class="text-blue">'+timeIn+'</span> / <span class="text-blue">'+timeOut+'</span></td></tr>' +
                    '<tr><td colspan="2">'+remarks+'</td></tr></table>');
            },error: function(xhr, status, error){
                console.log(xhr);
            }
        });
    }
});

function confirmSelectedDate(info)
{
    dateStart = moment(info.startStr);
    dateEnd = new Date(info.endStr);
    dateEnd.setDate( dateEnd.getDate() - 1 );
    // console.log(info.startStr);

    dateEnd = moment(dateEnd);
    Swal.fire({
        title: 'Preferred Date?',
        text: dateStart.format('MMMM DD, YYYY')+' to '+dateEnd.format('MMMM DD, YYYY'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value === true) {

            $('#preferred_date').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                startDate: dateStart.format('MM-DD-YYYY hh:mm a'),
                endDate: dateEnd.format('MM-DD-YYYY hh:mm a'),
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });
            $("#booking-modal").modal("toggle");
        }
    })
}

$(document).on('submit','#add-booking-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/bookings',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#add-booking-form').find('input, select, textarea').attr('disabled',true);
            $('#add-booking-form').find('.add-booking-btn').attr('disabled',true).val('Saving...');
        },success: function(response){
            console.log(response);
            if(response.success === true)
            {
                $('#add-booking-form').trigger('reset');
                $('#add-booking-form').find('#package, #status, #occasion').val('').change();
                Toast.fire({
                    type: 'success',
                    title: response.message
                });

                // window.location.reload();
                bookingModal.modal('toggle');
            }
            $('#add-booking-form').find('input, select, textarea').attr('disabled',false);
            $('#add-booking-form').find('.add-booking-btn').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            // console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            $('#add-booking-form').find('input, select, textarea').attr('disabled',false);
            $('#add-booking-form').find('.add-booking-btn').attr('disabled',false).val('Save');
        }
    });
    clear_errors('firstname','lastname','email','mobile_number','facebook_url','preferred_date','package','total_amount','status');
});
