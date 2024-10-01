@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Upload KPI</h5>
                </div>

                <div class="ibox-content">
                    <form method="POST" action="{{url('upload-kpi')}}" onsubmit="show()" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                Upload KPI :
                                <input type="file" name="file" class="form-control input-sm" required>
                            </div>
                            <div class="col-md-12">
                                &nbsp;
                                <button type="submit" class="btn btn-block btn-primary">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection