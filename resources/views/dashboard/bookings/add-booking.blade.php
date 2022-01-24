@extends('adminlte::page')

@section('title', ucwords($assignedStaycation->name))

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ucwords($assignedStaycation->name)}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('staycations.index')}}">Resorts</a></li>
                <li class="breadcrumb-item active">{{ucwords($assignedStaycation->name)}}</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @foreach($assignedStaycation->packages as $package)
                    <span class="right badge m-1" style="background-color: {{$package->color}}">{{$package->name}}</span>
                @endforeach
                    <span class="right badge m-1" style="background-color: #7d7a7a">Pencil Book</span>
                    <span class="right badge m-1" style="background-color: #3788d8">Custom</span>
            </div>
            <div class="card-body">
{{--                {!! $calendar->calendar() !!}--}}
                <div id="calendar"></div>

            </div>
        </div>
    </div>

    @if(auth()->user()->can('add booking') || auth()->user()->can('edit booking'))
        <!--add new package modal-->
        <div class="modal fade" id="booking-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Booking</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <x-add-booking-form :staycation="$assignedStaycation" :status="$status" :occasion="$occasion"></x-add-booking-form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
        <!--end add new staycation modal-->

        <!-- booking details modal -->
        <x-booking-details-modal></x-booking-details-modal>
    @endif
@stop
@section('plugins.Moment', true)
@section('plugins.Datatables', true)
@section('plugins.DateRangePicker', true)
@section('plugins.TempusDominus', true)
@section('plugins.Select2', true)
@section('plugins.FullCalendar', true)
@section('plugins.Stepper', true)
@section('plugins.DeviceDetector', true)
@section('css')

@stop

@section('js')
    <script src="{{asset('/js/number-formatter.js')}}"></script>
    <script src="{{asset('/js/errorDisplay.js')}}"></script>
    <script src="{{asset('/js/errorChecker.js')}}"></script>
{{--    <script src="{{asset('/js/bookings/removeBooking.js')}}"></script>--}}
    <script src="{{asset('/js/bookings/bookingDetails.js')}}"></script>
{{--    {!! $calendar->script() !!}--}}
    <script>
        let calendar;
        let blocked_dates;
        let events = {
            url: "/bookings/"+{{$assignedStaycation->id}},
            type: 'GET',
        };

        let calendarEl = document.getElementById('calendar')
                document.addEventListener('DOMContentLoaded', function() {
                    displayCalendar(events);
                });

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
        // document.addEventListener('DOMContentLoaded', function () {
        //     window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        // });

        let stepper = new Stepper($('.bs-stepper')[0])

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
                        // console.log(response);

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
                            minDate: new Date(),
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

        function confirmSelectedDate(start, end)
        {
            dateStart = moment(start);
            dateEnd = new Date(end);
            dateEnd.setDate( dateEnd.getDate() - 1 );
            dateEnd = moment(dateEnd);

            let preferred_date = isMobile() ? "Preferred Date Start" : 'Preferred Date?';
            let text = isMobile() ? dateStart.format('MMMM DD, YYYY') : dateStart.format('MMMM DD, YYYY')+' to '+dateEnd.format('MMMM DD, YYYY');

            Swal.fire({
                title: preferred_date,
                text: text,
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
                        minDate: new Date(),
                        locale: {
                            format: 'MM/DD/YYYY hh:mm A'
                        },isInvalidDate: function (date){
                            return blocked_dates.reduce(function(bool, range) {
                                return bool || (date >= range.start && date <= range.end);
                            }, false);
                        }
                    });
                    $("#booking-modal").modal("toggle");
                }
            })
        }



        $('#preferred_date').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            },
            minDate: new Date(),
            linkedCalendars: false,
            isInvalidDate: function (date){
                return blocked_dates.reduce(function(bool, range) {
                    return bool || (date >= range.start && date <= range.end);
                }, false);
                // return ;
            }
        });

        // console.log(moment().format("YYYY-MM-DD"))

        function blockedDates()
        {
            $.ajax({
                'url' : '{{route('bookings.blocked.dates',['staycation' => $assignedStaycation->id])}}',
                'type' : 'GET',
                success: function(response){
                    // console.log(response);
                    blocked_dates = [];
                    for(let ctr = 0; ctr < response.length; ctr++)
                    {
                        blocked_dates[ctr] = {
                            'start' : moment(response[ctr].start),
                            'end' : moment(response[ctr].end)
                        };
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }

        blockedDates();

        let bookingDetailsModal = $('#booking-details-modal');
        function displayCalendar(event)
        {

            let dateNow = moment(new Date()).format('YYYY-MM-DD');
            let bookingForm = $('.form-submit');
            calendar = new FullCalendar.Calendar(calendarEl,
                {
                    initialView: "dayGridMonth",
                    height: "auto",
                    headerToolbar: {
                        left: "prev,next today myCustomButton",
                        center: "title",
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    slotEventOverlap: true,
                    dayMaxEventRows: true,
                    firstDay: 0,
                    displayEventTime: true,
                    selectable: true,
                    customButtons: {
                        myCustomButton: {
                            id: "create-booking-btn",
                            text: "Create",
                            click: function() {
                                stepper.reset();
                                customBookingButton();
                                bookingModal.modal("toggle");
                            }
                        }
                    },
                    select: function(info){
                        if(!isMobile())
                        {
                            let selectedDate = info.startStr;
                            bookingForm.find('.text-danger').remove();
                            bookingForm.find('#package, #status, #occasion').val('').change();

                            if(moment(selectedDate).isBefore(dateNow))
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Please select another date'
                                });
                            }else{
                                // confirmSelectedDate(info);
                                confirmSelectedDate(info.startStr, info.endStr);
                            }
                        }
                    },
                    eventClick: function(info) {
                        // alert('Event: ' + info.event.title);
                        bookingId = info.event.id;
                        bookingDetailsModal.modal('toggle');
                        bookingDetails(info.event.id, bookingDetailsModal);

                        bookingDetailsModal.find('.remove-booking').attr('id',info.event.id);
                        bookingDetailsModal.find('.edit-booking').attr('id',info.event.id);
                        // change the border color just for fun
                        info.el.style.borderColor = 'red';
                    },
                    dateClick: function(info) {
                        stepper.reset();
                        if(isMobile())
                        {
                            if(moment(moment(info.dateStr)).isBefore(dateNow))
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Please select another date'
                                });
                            }else{
                                let end = moment(info.dateStr).add(1,'d');
                                confirmSelectedDate(moment(info.dateStr), new Date(end));
                            }
                        }
                    },
                    events: event
                },
            );
            calendar.render();
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
                        displayCalendar(events);
                        bookingModal.modal('toggle');
                        stepper.reset();
                    }
                    $('#add-booking-form').find('input, select, textarea').attr('disabled',false);
                    $('#add-booking-form').find('.add-booking-btn').attr('disabled',false).val('Save');
                },error: function(xhr, status, error){
                    console.log(xhr);
                    let items = xhr.responseJSON.errors;

                    if(Object.keys(items).length > 0)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please fill up required fields!'
                        });
                    }

                if(xhr.responseJSON.success === false && xhr.responseJSON.date === false)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: xhr.responseJSON.message
                        });
                    }
                    errorDisplay(xhr.responseJSON.errors);
                    $('#add-booking-form').find('input, select, textarea').attr('disabled',false);
                    $('#add-booking-form').find('.add-booking-btn').attr('disabled',false).val('Save');
                }
            });
            clear_errors('firstname','lastname','email','mobile_number','facebook_url','preferred_date','package','total_amount','status','pax');
        });


        let bookingId;
        function removeBooking()
        {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        url : '/bookings/'+bookingId,
                        type: 'DELETE',
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            console.log(response);
                            displayCalendar(events);
                            bookingDetailsModal.modal('toggle');

                            if(response.success === true)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: response.message
                                });
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });
                }
            })
        }

        $(document).on('click','.remove-booking',function(){
            removeBooking();
        });

        $(document).on('click','.edit-booking',function(){
            console.log(bookingId);
        });

    </script>
{{--    <script src="{{asset('/js/StayCation/booking.js')}}"></script>--}}
@stop
