@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.member.title_singular') }}
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="photo">{{ trans('cruds.member.fields.photo') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                         id="photo-dropzone">
                    </div>
                    @if ($errors->has('photo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('photo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.member.fields.photo_helper') }}</span>
                </div>

                @if (config('domains')[config('app.url')]['minor'] == true)
                    <div class="row form-group">
                        <div class="col-md-2">
                            {{ trans('global.minor') }}
                        </div>
                        <div class="col-md-1 text-right">
                            <label class="c-switch c-switch-3d c-switch-success">
                                <input type="checkbox" name="minor" id="minor" value="yes" class="c-switch-input">
                                <span class="c-switch-slider shadow-none"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row form-group" id="parent" style="display: none">
                        <div class="col-md-3">
                            <label for="">{{ trans('global.parent_phone') }}</label>
                            <input type="text" class="form-control" name="parent_phone"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ trans('global.parent_details') }}</label>
                            <input type="text" class="form-control" name="parent_details">
                        </div>
                    </div>
                @endif

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="required" for="name">{{ trans('cruds.member.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name"
                               id="name" value="{{ old('name', '') }}" required>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.name_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label class="required" for="phone">{{ trans('cruds.member.fields.phone') }}</label>
                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text"
                               name="phone" id="phone" value="{{ old('phone', '') }}" required
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                               min="10" max="10">

                        @if ($errors->has('phone'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.phone_helper') }}</span>
                    </div>

                    {{-- {{ config('domains')[config('app.url')]['national_id'] == true ? 'required' : '' }} --}}


                    {{-- @if($selected_branch != null) --}}
                        <div class="col-md-3">
                            <label class="required" for="branch">Branch</label>
                            <select name="branch_id" id="branch_id"
                                    class="form-control {{ $errors->has('branch_id') ? 'is-invalid' : '' }}"
                                    {{is_null($selected_branch) ? '' : 'disabled' }} required>
                                @foreach ($branches as $id => $entry)
                                    <option value="{{ $id }}" {{ ($selected_branch ? $selected_branch->id == $id : '') ? 'selected' : '' }} >
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
                    {{-- @endif
                    <div class="col-md-3">
                        <label class="required" for="branch">Branch</label>
                        <select name="branch_id" id="branch_id"
                                class="form-control {{ $errors->has('branch_id') ? 'is-invalid' : '' }}"
                                {{is_null($selected_branch) ? '' : 'disabled' }} required>
                            @foreach ($branches as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('branch_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('branch_id') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_helper') }}</span>
                    </div> --}}

                    <div class="col-md-3">
                        <label class="{{ config('domains')[config('app.url')]['email'] == true ? 'required' :''}}"
                               for="email">{{ trans('cruds.member.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                               name="email" id="email"
                               value="{{ old('email', '') }}" {{ config('domains')[config('app.url')]['email'] == true ? 'required' :''}}>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.email_helper') }}</span>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="referral_member">{{ trans('cruds.lead.fields.referral_member') }}</label>
                        <input type="text" class="form-control"
                               placeholder="{{ trans('cruds.lead.fields.referral_member') }}" name="referral_member"
                               id="referral_member" onblur="referralMember()" value="{{ old('referral_member') }}">
                        <small class="text-danger" id="referral_member_msg"></small>
                    </div>

                    <div class="col-md-3">
                        <label class="required"
                               for="member_code">{{ trans('cruds.member.fields.member_code') }}</label>
                        <input class="form-control {{ $errors->has('member_code') ? 'is-invalid' : '' }}" type="text"
                               name="member_code" id="member_code" value="{{ $last_member_code + 1  }}" required
                               @cannot('edit_member_code')
                                   readonly
                                @endcannot>
                        @if ($errors->has('member_code'))
                            <div class="invalid-feedback">
                                {{ $errors->first('member_code') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.member_code_helper') }}</span>
                    </div>
                    <input type="hidden" value="{{ \App\Models\Status::first() ? \App\Models\Status::first()->id : 1 }}"
                           id="status_id" name="status_id">
                    <div class="col-md-3">
                        <label class="required" for="source_id">{{ trans('cruds.member.fields.source') }}</label>
                        <select class="form-control select2 {{ $errors->has('source') ? 'is-invalid' : '' }}"
                                name="source_id" id="source_id" required>
                            <option disabled selected hidden>{{ trans('global.pleaseSelect') }}</option>
                            @foreach ($sources as $id => $entry)
                                <option value="{{ $id }}" {{ old('source_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('source'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.source_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label for="whatsapp_number">{{ trans('global.whatsapp') }}</label>
                        <input class="form-control {{ $errors->has('whatsapp_number') ? 'is-invalid' : '' }}"
                               type="text"
                               name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        @if ($errors->has('whatsapp_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('whatsapp_number') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="{{ config('domains')[config('app.url')]['national_id'] == true ? 'required' :''}}"
                               for="national">{{ trans('cruds.member.fields.national') }}</label>
                        <input class="form-control {{ $errors->has('national') ? 'is-invalid' : '' }}" type="text"
                               name="national" id="national" value="{{ old('national', '') }}"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" {{ config('domains')[config('app.url')]['national_id'] == true ? 'min="10" max="10" required' :''}}>
                        @if ($errors->has('national'))
                            <div class="invalid-feedback">
                                {{ $errors->first('national') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.national_helper') }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="required" for="dob">{{ trans('cruds.member.fields.dob') }}</label>
                        <input class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="date"
                               name="dob" id="dob" value="{{ old('dob') ?? date('1990-01-01') }}" required
                               max="{{ date('Y-m-d') }}">
                        @if ($errors->has('dob'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dob') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.dob_helper') }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="required">{{ trans('cruds.member.fields.gender') }}</label>
                        <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender"
                                id="gender" required>
                            <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>
                                {{ trans('global.pleaseSelect') }}</option>
                            @foreach (App\Models\Lead::GENDER_SELECT as $key => $label)
                                <option value="{{ $key }}"
                                        {{ old('gender', '') === (string) $key ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('gender'))
                            <div class="invalid-feedback">
                                {{ $errors->first('gender') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.gender_helper') }}</span>
                    </div>

                    <div class="col-md-3">
                        <label class="required"
                               for="sales_by_id">{{ trans('cruds.member.fields.sales_by') }}</label>
                        <select class="form-control select2 {{ $errors->has('sales_by') ? 'is-invalid' : '' }}"
                                name="sales_by_id" id="sales_by_id" required>
                            <option disabled selected hidden>{{ trans('global.pleaseSelect') }}</option>
                            @foreach ($sales_bies as $id => $entry)
                                <option value="{{ $id }}" {{ old('sales_by_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('sales_by'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sales_by') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.sales_by_helper') }}</span>
                    </div>
                </div>


                <div class="form-group row">

                    <div class="col-md-6">
                        <label class="required" for="address">Area </label>
                        <select name="address_id" id="address_id"
                                class="form-control select2 {{ $errors->has('address_id') ? 'is-invalid' : '' }}"
                                required>
                            <option disabled selected hidden>{{ trans('global.pleaseSelect') }}</option>
                            @foreach($addresses as $id => $entry)
                                <option value="{{ $id }}" {{ old('address_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('address_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_helper') }}</span>
                    </div>

                    <div class="col-md-6">
                        <label for="address_details">{{ trans('cruds.lead.fields.address_details') }}</label>
                        <input type="text"
                               class="form-control {{ $errors->has('address_details') ? 'is-invalid' : '' }}"
                               name="address_details"
                               id="address_details" value="{{ old('address_details') }}"/>
                        @if ($errors->has('address_details'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_details') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.lead.fields.address_details_helper') }}</span>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="notes">{{ trans('cruds.member.fields.notes') }}</label>
                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes"
                                  id="notes">{{ old('notes') }}</textarea>
                        @if ($errors->has('notes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('notes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.member.fields.notes_helper') }}</span>
                    </div>
                </div>

            </div>
        </div>
        {{-- subscription details --}}
        @include('partials.subscription_details')

        {{-- invoice details --}}
        @include('partials.invoices_details')


        {{-- payments details --}}
        @include('partials.payments_details')

        {{-- reminders --}}
        @include('partials.invoice_reminder')

        <div class="card-footer">
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    @include('partials.create_member_transfer_js')
    <script>
        $("#minor").change(function () {
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
    </script>
@endsection

