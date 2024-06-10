@if($errors->any())
    <div class="form-group alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <strong>{{$errors->first()}}</strong>
    </div>
@endif