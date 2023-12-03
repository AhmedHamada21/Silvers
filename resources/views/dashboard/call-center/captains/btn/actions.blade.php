<div class="mb-1 btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">{{ trans('general.processes') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('CallCenterCaptains.show', $captain->profile->uuid) }}"
            class="modal-effect btn btn-sm btn-dark dropdown-item" style="text-align: center !important">
            <span class="icon text-info text-dark">
                <i class="fa fa-edit"></i>
                Profile
            </span>
        </a>
    </div>
</div>