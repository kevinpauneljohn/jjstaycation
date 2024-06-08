<table id="booking-lists" class="table table-hover w-100">
    <thead>
        <tr>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Staycation</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>No. of Pax</th>
            <th>Occasion</th>
            <th>status</th>
            <th>Booked by</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

@push('js')
    <script>
        $(function() {
            $('#booking-lists').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get.bookings.lists') !!}',
                columns: [
                    { data: 'start', name: 'start'},
                    { data: 'end', name: 'end'},
                    { data: 'staycation_id', name: 'staycation_id'},
                    { data: 'customer_id', name: 'customer_id'},
                    { data: 'total_amount', name: 'total_amount'},
                    { data: 'pax', name: 'pax'},
                    { data: 'occasion', name: 'occasion'},
                    { data: 'status', name: 'status'},
                    { data: 'booked_by', name: 'booked_by'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
    </script>
@endpush
