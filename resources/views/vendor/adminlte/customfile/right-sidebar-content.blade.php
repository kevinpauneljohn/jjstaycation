<div class="p-3" style="overflow: hidden;word-wrap: break-word;">
        <h5>Activities</h5><hr class="mb-2">
        @php
            $activities = \Spatie\Activitylog\Models\Activity::where('properties->model','=','bookings')
            ->where('properties->staycation_id','=',\Illuminate\Support\Facades\Request::segment(2))
            ->orderBy('created_at','desc')->get();
        @endphp
    <div style="overflow-y: scroll;height: 80vh; /* For 100% screen height */" id="style-3">
        @foreach($activities as $activity)
            <span class="text-muted"><i class="far fa-clock"></i> {{\Illuminate\Support\Carbon::parse($activity->created_at)->format('Y-m-d h:s a')}}</span>
            <div class="mb-4">
                <span class="text-bold text-maroon">{{ucfirst($activity->properties['username'])}}</span>
                <span class="text-orange">{{$activity->description}}</span> a booking.
                <p>
                    with schedule from <span class="text-primary">{{\Illuminate\Support\Carbon::parse(collect($activity->properties['bookingInformation'])['start'])->format('Y-m-d h:s a')}}</span> to
                    <span class="text-primary">{{\Illuminate\Support\Carbon::parse(collect($activity->properties['bookingInformation'])['end'])->format('Y-m-d h:s a')}}</span><br/>
                    @php $customer = collect($activity->properties['bookingInformation']['customer']) @endphp
                    Guest name: <span class="text-success">{{ucfirst($customer['firstname'])}} {{ucfirst($customer['lastname'])}}</span><br/>
                    Package: <span class="text-success">{{ucwords(collect($activity->properties['bookingInformation'])['title'])}}</span><br/>
                    Amount: <span class="text-success">{{number_format(collect($activity->properties['bookingInformation'])['total_amount'],2)}}</span><br/>
                    Pax: <span class="text-success">{{collect($activity->properties['bookingInformation'])['pax']}}</span>
                </p>
            </div>
            {{--                    <div class="mb-4">{{collect($activity->properties['bookingInformation'])['id']}}</div>--}}
            <hr class="mb-2">
        @endforeach
    </div>
</div>
