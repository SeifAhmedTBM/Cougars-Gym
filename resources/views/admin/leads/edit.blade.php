@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            <h5>{{ trans('global.edit') }} {{ trans('cruds.lead.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.leads.update', [$lead->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                @if (config('domains')[config('app.url')]['minor'] == true)
                    <div class="row form-group">
                        <div class="col-md-2">
                            {{ trans('global.minor') }}
                        </div>
                        <div class="col-md-1 text-right">
                            <label class="c-switch c-switch-3d c-switch-success">
                                <input type="checkbox" name="minor" id="minor" value="{{ 'yes' ?? 'no' }}"
                                    class="c-switch-input" {{ !is_null($lead->parent_phone) ? 'checked' : '' }}>
                                <span class="c-switch-slider shadow-none"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row form-group" id="parent"
                        style="{{ $lead->parent_phone == null ? 'display:none' : '' }}">
                        <div class="col-md-3">
                            <label for="">{{ trans('global.parent_phone') }}</label>
                            <input type="text" class="form-control" name="parent_phone"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                value="{{ $lead->parent_phone }}">
                        </div>
                        <div class="col-md-3">
                            <label for="">{{ trans('global.parent_phone') }} 2</label>
                            <input type="text" class="form-control" name="parent_phone_two"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                value="{{ $lead->parent_phone_two }}">
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ trans('global.parent_details') }}</label>
                            <input type="text" class="form-control" name="parent_details"
                                value="{{ $lead->parent_details }}">
                        </div>
                    </div>
                @endif

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="required" for="name">{{ trans('cruds.lead.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', $lead->name) }}" required>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.name_helper') }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="required" for="phone">{{ trans('cruds.lead.fields.phone') }}</label>
                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text"
                            name="phone" id="phone" value="{{ old('phone', $lead->phone) }}" required
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                            min="10" max="10" {{ !is_null($lead->parent_phone) ? 'disabled' : '' }}>
                        @if ($errors->has('phone'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.phone_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label class="required" for="branch">Branch</label>
                        <select name="branch_id" id="branch_id"
                            class="form-control {{ $errors->has('branch_id') ? 'is-invalid' : '' }}" required>
                            @foreach ($branches as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('branch_id') ? old('branch_id') : $lead->branch->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('branch_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('branch_id') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_helper') }}</span>
                    </div>


                    <div class="col-md-3">
                        <label class="required" for="address">{{ trans('cruds.lead.fields.address') }}</label>
                        <select name="address_id" id="address_id"
                            class="form-control select2 {{ $errors->has('address_id') ? 'is-invalid' : '' }}">
                            @foreach ($addresses as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('address_id') ? old('address_id') : $lead->address->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('address_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_helper') }}</span>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="whatsapp_number">{{ trans('global.whatsapp') }}</label>
                        <input class="form-control {{ $errors->has('whatsapp_number') ? 'is-invalid' : '' }}"
                            type="text" name="whatsapp_number" id="whatsapp_number"
                            value="{{ old('whatsapp_number') ?? $lead->whatsapp_number }}"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        @if ($errors->has('whatsapp_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('whatsapp_number') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label for="national"
                            class="{{ config('domains')[config('app.url')]['national_id'] == true ? '' : '' }}">{{ trans('cruds.lead.fields.national') }}</label>
                        <input class="form-control {{ $errors->has('national') ? 'is-invalid' : '' }}" type="text"
                            name="national" id="national" value="{{ old('national', $lead->national) }}"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                            {{ !is_null($lead->parent_phone) ? 'disabled' : '' }}>
                        @if ($errors->has('national'))
                            <div class="invalid-feedback">
                                {{ $errors->first('national') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.national_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label class="required" for="source_id">{{ trans('cruds.lead.fields.source') }}</label>
                        <select class="form-control select2 {{ $errors->has('source') ? 'is-invalid' : '' }}"
                            name="source_id" id="source_id" required>
                            @foreach ($sources as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('source_id') ? old('source_id') : $lead->source->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('source'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.source_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label for="dob">{{ trans('cruds.lead.fields.dob') }}</label>
                        <input class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="date"
                            name="dob" id="dob" value="{{ old('dob', $lead->dob) }}" required
                            max="{{ date('Y-m-d') }}">
                        @if ($errors->has('dob'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dob') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.dob_helper') }}</span>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="required">{{ trans('cruds.lead.fields.gender') }}</label>
                        <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender"
                            id="gender" required>
                            <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>
                                {{ trans('global.pleaseSelect') }}</option>
                            @foreach (App\Models\Lead::GENDER_SELECT as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('gender', $lead->gender) === (string) $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('gender'))
                            <div class="invalid-feedback">
                                {{ $errors->first('gender') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.gender_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label class="required">{{ trans('cruds.lead.fields.followup') }}</label>
                        <input class="form-control date {{ $errors->has('followup') ? 'is-invalid' : '' }}"
                            type="text" name="followup" id="followup"
                            value="{{ old('followup') ?? date('Y-m-d') }}" required>
                        @if ($errors->has('followup'))
                            <div class="invalid-feedback">
                                {{ $errors->first('followup') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.followup_helper') }}</span>
                    </div>

                    @if (config('domains')[config('app.url')]['sports_option'] == true)
                        <div class="col-md-3">
                            <label>{{ trans('global.sport') }}</label>
                            <select name="sport_id" id="sport_id" class="form-control select2">
                                <option value="{{ null }}" selected hidden>{{ trans('global.pleaseSelect') }}
                                </option>
                                @foreach ($sports as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('sport_id', $lead->sport_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('sport_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sport_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.lead.fields.followup_helper') }}</span>
                        </div>
                    @endif

                    <div class="col-md-3">
                        <label class="required" for="sales_by_id">{{ trans('cruds.lead.fields.sales_by') }}</label>
                        <select class="form-control select2 {{ $errors->has('sales_by') ? 'is-invalid' : '' }}"
                            name="sales_by_id" id="sales_by_id" required>
                            @foreach ($sales_bies as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('sales_by_id') ? old('sales_by_id') : $lead->sales_by->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('sales_by'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sales_by') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.sales_by_helper') }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 mt-4">
                        <h5>Invitation</h5>
                    </div>

                    <div class="col-md-1">
                        <label class="c-switch c-switch-3d c-switch-success my-4">
                            <input type="checkbox" name="invitation" id="invitation"
                                value="{{ $lead->invitation == true ? true : false }}" class="c-switch-input"
                                {{ $lead->invitation == true ? 'checked' : '' }}>
                            <span class="c-switch-slider shadow-none"></span>
                        </label>
                    </div>

                    <div class="col-md-3" id="trainer_div"
                        style="{{ $lead->invitation == null ? 'display:none' : '' }}">
                        <label for="trainer_id" class="required">Coach</label>
                        <select name="trainer_id" id="trainer_id" class="form-control select2">
                            @foreach ($trainers as $id => $name)
                                <option value="{{ $id }}" {{ $lead->trainer_id == $id ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="address_details">{{ trans('cruds.lead.fields.address_details') }}</label>
                        <textarea class="form-control {{ $errors->has('address_details') ? 'is-invalid' : '' }}" name="address_details"
                            id="address_details">{{ old('address_details') ?? $lead->address_details }}</textarea>
                        @if ($errors->has('address_details'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_details') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_details_helper') }}</span>
                    </div>
                    <div class="col-md-6">
                        <label for="notes">{{ trans('cruds.lead.fields.notes') }}</label>
                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{{ old('notes', $lead->notes) }}</textarea>
                        @if ($errors->has('notes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('notes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.notes_helper') }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="medical_background">{{ trans('cruds.lead.fields.medical_background') }}</label>
                        <textarea class="form-control {{ $errors->has('medical_background') ? 'is-invalid' : '' }}"
                            name="medical_background" id="medical_background" rows="3">{{ old('medical_background', $lead->medical_background) }}</textarea>
                        @if ($errors->has('medical_background'))
                            <div class="invalid-feedback">
                                {{ $errors->first('medical_background') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.medical_background_helper') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('scripts')
    <script>
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.leads.storeMedia') }}',
            maxFilesize: 5, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').find('input[name="photo"]').remove()
                $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($lead) && $lead->photo)
                    var file = {!! json_encode($lead->photo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>

    @if (config('domains')[config('app.url')]['national_id'] == true)
        <script>
            // $('#national').on('keyup', function() {
            //     if ($('#national').val().length >= 6) {
            //         $('#national').removeClass('is-invalid').addClass('is-valid');
            //     } else {
            //         $('#national').removeClass('is-valid').addClass('is-invalid');
            //     }
            // })

            $('#phone').on('keyup', function() {
                if ($('#phone').val().length == 10) {
                    $('#phone').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#phone').removeClass('is-valid').addClass('is-invalid');
                }
            })
        </script>
    @endif

    <script>
        $("#minor").change(function() {
            if (this.checked == true) {
                $(".hideMe").slideDown();
                $('#parent').slideDown();
                $('#phone').attr('disabled', true);
                $('#national').attr('disabled', true);
            } else {
                $(".hideMe").slideUp();
                $('#parent').slideUp();
                $('#parent_phone').val(null);
                $('#parent_details').val(null);
                $('#phone').attr('disabled', false);
                $('#national').attr('disabled', false);
            }
        });

        $("#invitation").change(function() {
            if (this.checked == true) {
                $("#trainer_div").slideDown();
            } else {
                $("#trainer_div").slideUp();
            }
        });
    </script>
@endsection
