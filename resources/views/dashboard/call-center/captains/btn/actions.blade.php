<div class="mb-1 btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">{{ trans('general.processes') }}</button>
    <div class="dropdown-menu">
        @if(get_user_data()->id == $captain->callcenter_id)
            <a href="{{ route('CallCenterCaptains.show', $captain->profile->uuid) }}"
               class="modal-effect btn btn-sm btn-dark dropdown-item" style="text-align: center !important">
            <span class="icon text-info text-dark">
                <i class="fa fa-edit"></i>
                Profile
            </span>
            </a>
        @endif

        <a href="{{ route('CallCenterCaptains.trips',  $captain->profile->uuid) }}"
            class="modal-effect btn btn-sm btn-dark dropdown-item" style="text-align: center !important">
            <span class="icon text-info text-dark">
                <i class="fa fa-edit"></i>
                My Trips
            </span>
        </a>

        <button type="button" class="modal-effect btn btn-sm btn-success dropdown-item"
                    style="text-align: center !important" data-toggle="modal" data-target="#newCar{{$captain->id}}"
                    data-effect="effect-scale">
            <span class="icon text-dark text-bold">
                <i class="fa fa-edit"></i>
                New Car
            </span>
        </button>
    </div>
</div>
@include('dashboard.call-center.captains.btn.modals.newCar')