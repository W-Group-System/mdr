<div class="modal" id="editScores{{$score->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit MDR Scores</h5>
            </div>
            <form action="{{url('submit_scores/'.$score->id)}}" method="post" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Process Improvement Scores :
                            <input type="number" name="process_improvement_scores" step=".01" class="form-control input-sm" value="{{$score->pd_scores}}">
                        </div>
                        {{-- <div class="col-md-12">
                            Innovation Scores :
                            <input type="number" name="innovation_scores" step=".01" class="form-control input-sm" value="{{$score->innovation_scores}}">
                        </div> --}}
                        <div class="col-md-12">
                            Timeliness : 
                            <input type="number" step=".01" name="timeliness" class="form-control input-sm" value="{{$score->timeliness}}">
                        </div>
                        {{-- <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10">{{$score->remarks}}</textarea>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>