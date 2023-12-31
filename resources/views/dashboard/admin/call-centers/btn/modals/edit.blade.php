<div class="modal fade" id="edit{{$callCenter->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('general.edit') .' '. $callCenter->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('callCenters.update', $callCenter->id)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Start Name -->
                    <div class="form-group">
                        <label for="name">{{ trans('admins.name') }}</label>
                        <input type="text" class="form-control" required name="name" id="name"
                            value="{{$callCenter->name}}">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- End Name -->

                    <!-- Start Email -->
                    <div class="form-group">
                        <label for="email">{{ trans('admins.email') }}</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                            value="{{$callCenter->email}}">
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- End Email -->

                    <!-- Start Phone -->
                    <div class="form-group">
                        <label for="phone">{{ trans('admins.phone') }}</label>
                        <input type="number" class="form-control" required name="phone" id="phone"
                            value="{{$callCenter->phone}}">
                        @error('phone')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- End Phone -->

                    <!-- Start Status Status -->
                    <div class="p-1 form-group">
                        <label for="status">Status</label>
                        <select name="status" class="p-1 form-control">
                            <option selected disabled>Select <span class="text-primary">{{$callCenter->name}}</span>
                                Status...</option>
                            <option value="active" {{ (old('status', $callCenter->status) == 'active') ? 'selected' : ''}}>
                                {{ trans('general.active') }}
                            </option>
                            <option value="inactive" {{ (old('status', $callCenter->status) == 'inactive') ? 'selected' : '' }}>
                                {{ trans('general.inactive') }}
                            </option>
                        </select>
                        @error('status')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- End Status Selected -->

                    <!-- Start Type -->
                    <div class="p-1 form-group">
                        <label for="type">Type</label>
                        <select name="type" class="p-1 form-control">
                            <option selected disabled>Select <span class="text-primary">{{$callCenter->name}}</span>
                                Type...
                            </option>
                            @if(auth('admin')->check())
                                <option value="manager" {{ (old('type', $callCenter->type) == 'manager') ? 'selected' : ''}}>
                                    Manager
                                </option>
                            @endif

                            <option value="callcenter" {{ (old('type', $callCenter->type) == 'callcenter') ? 'selected' : '' }}>
                                Call-Center
                            </option>
                        </select>
                        @error('type')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- End Type Selected -->


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close')
                            }}</button>
                        <button type="submit" class="btn btn-success">{{ trans('general.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
