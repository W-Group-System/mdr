@extends('layouts.app')

@section('css')
{{-- switch --}}
<link href="{{ asset('css/plugins/switchery/switchery.css') }}" rel="stylesheet">

<style>
    .switchery-small {
        height: 20px !important;
        width: 33px !important;
    }

    .switchery-small>small {
        height: 20px !important;
        width: 20px !important;
    }
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-6">
            <form method="post" action="{{ url('store_access_module') }}" onsubmit="show()">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="ibox float-e-margins">
                    <div class="ibox-title" style="display: flex; justify-content:space-between;">
                        <h5>User Access Module</h5>

                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>

                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Create <input type="checkbox" class="js-switch" id="createCheckAll"></th>
                                        <th>Read/View <input type="checkbox" class="js-switch" id="readCheckAll"></th>
                                        <th>Update <input type="checkbox" class="js-switch" id="updateCheckAll"></th>
                                        <th>Delete <input type="checkbox" class="js-switch" id="deleteCheckAll"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modules as $module)
                                    @php
                                    $create_module = ($user->access_module)->where('create','on')->where('module_id','!=',null)->pluck('module_id')->toArray();
                                    $read_module = ($user->access_module)->where('read','on')->where('module_id','!=',null)->pluck('module_id')->toArray();
                                    $update_module = ($user->access_module)->where('update','on')->where('module_id','!=',null)->pluck('module_id')->toArray();
                                    $delete_module = ($user->access_module)->where('delete','on')->where('module_id','!=',null)->pluck('module_id')->toArray();
                                    @endphp

                                    @if(count($module->submodule) > 0)
                                    <tr>
                                        <td colspan="5"><b>{{ $module->module_name }}</b></td>
                                    </tr>
                                    @foreach ($module->submodule as $submodule)
                                    @php
                                        $create_submodule = ($user->access_module)->where('create','on')->where('submodule_id','!=',null)->pluck('submodule_id')->toArray();
                                        $read_submodule = ($user->access_module)->where('read','on')->where('submodule_id','!=',null)->pluck('submodule_id')->toArray();
                                        $update_submodule = ($user->access_module)->where('update','on')->where('submodule_id','!=',null)->pluck('submodule_id')->toArray();
                                        $delete_submodule = ($user->access_module)->where('delete','on')->where('submodule_id','!=',null)->pluck('submodule_id')->toArray();
                                    @endphp
                                    
                                    <tr>
                                        <td>{{ $submodule->submodule_name }}</td>
                                        <td>
                                            <input type="hidden" name="submodule_access[{{ $submodule->id }}][create]" value="">
                                            <input type="checkbox" class="js-switch" name="submodule_access[{{ $submodule->id }}][create]" value="on" @if(in_array($submodule->id, $create_submodule)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="submodule_access[{{ $submodule->id }}][read]" value="">
                                            <input type="checkbox" class="js-switch" name="submodule_access[{{ $submodule->id }}][read]" value="on" @if(in_array($submodule->id, $read_submodule)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="submodule_access[{{ $submodule->id }}][update]" value="">
                                            <input type="checkbox" class="js-switch" name="submodule_access[{{ $submodule->id }}][update]" value="on" @if(in_array($submodule->id, $update_submodule)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="submodule_access[{{ $submodule->id }}][delete]" value="">
                                            <input type="checkbox" class="js-switch" name="submodule_access[{{ $submodule->id }}][update]" value="on" @if(in_array($submodule->id, $delete_submodule)) checked @endif>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td>{{ $module->module_name }}</td>
                                        <td>
                                            <input type="hidden" name="module_access[{{ $module->id }}][create]" value="">
                                            <input type="checkbox" class="js-switch" name="module_access[{{ $module->id }}][create]" value="on" @if(in_array($module->id, $create_module)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="module_access[{{ $module->id }}][read]" value="">
                                            <input type="checkbox" class="js-switch" name="module_access[{{ $module->id }}][read]" value="on" @if(in_array($module->id, $read_module)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="module_access[{{ $module->id }}][update]" value="">
                                            <input type="checkbox" class="js-switch" name="module_access[{{ $module->id }}][update]" value="on" @if(in_array($module->id, $update_module)) checked @endif>
                                        </td>
                                        <td>
                                            <input type="hidden" name="module_access[{{ $module->id }}][delete]" value="">
                                            <input type="checkbox" class="js-switch" name="module_access[{{ $module->id }}][delete]" value="on" @if(in_array($module->id, $delete_module)) checked @endif>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Audit Logs</h5>
                </div>

                <div class="ibox-content" style="height: 75vh;">
                    <ul class="nav" style="height:100%; overflow-y:auto;">
                        @foreach ($user->audit->sortByDesc('id') as $audit)
                        <li><small>Event :</small>
                            @if($audit->event == "created")
                            <span class="label label-primary">
                                @elseif($audit->event == "updated")
                                <span class="label label-warning">
                                    @elseif($audit->event == "deleted")
                                    <span class="label label-danger">
                                        @endif
                                        {{ $audit->event }}
                                    </span>
                        </li>
                        <li><small>Old Values :</small> {{ $audit->old_values }} </li>
                        <li><small>New Values :</small> {{ $audit->new_values }}</li>
                        <hr>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/plugins/switchery/switchery.js') }}"></script>
<script>
    $(document).ready(function() {
        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            new Switchery(elem, { color: '#1AB394', size: 'small' })
        })

        // $("#createCheckAll").on('change', function(){
        //     if($(this).is(':checked'))
        //     {
        //         $(".createCheck").prop('checked', true)
        //     }
        //     else
        //     {
        //         $(".createCheck").prop('checked', false)
        //     }
        // })

        // $("#readCheckAll").on('change', function(){
        //     if($(this).is(':checked'))
        //     {
        //         $(".readCheck").prop('checked', true)
        //     }
        //     else
        //     {
        //         $(".readCheck").prop('checked', false)
        //     }
        // })

        // $("#updateCheckAll").on('change', function(){
        //     if($(this).is(':checked'))
        //     {
        //         $(".updateCheck").prop('checked', true)
        //     }
        //     else
        //     {
        //         $(".updateCheck").prop('checked', false)
        //     }
        // })

        // $("#deleteCheckAll").on('change', function(){
        //     if($(this).is(':checked'))
        //     {
        //         $(".deleteCheck").prop('checked', true)
        //     }
        //     else
        //     {
        //         $(".deleteCheck").prop('checked', false)
        //     }
        // })
    })
</script>
@endpush