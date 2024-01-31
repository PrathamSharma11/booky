@extends('admin.layouts.master')
@section('content')




                    <form action="{{ url('/admin/category/update') }}" method="post" id="createCategory"
                        enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <div class="row form-group">
                            <div class="col col-md-3">

                            <label for="text-input" class=" form-control-label" style="margin-left: 70px;">Name</label>
                            </div>

                            <div class="col-12 col-md-9">
                                <input type="text" id="name" name="name" placeholder="Text" class="form-control">
                                <small class="form-text text-muted">This is a help text</small>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="textarea-input"class=" form-control-label" style="margin-left: 70px;">Description</label></div>
                            <div class="col-12 col-md-9">
                                <textarea name="description" id="textarea-input" rows="9" placeholder="Content..." class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="file-input" class=" form-control-label" style="margin-left: 70px;">Category Image</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="file" id="file-input" name="image" class="form-control-file"
                                    style='height: 32px;' onchange="loadFilee(event)">
                                <img id="output" style="max-width: 150px; max-height: 150px; border-radius: 0%;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-md" style="margin-left: 70px;">
                            <i class="fa fa-dot-circle-o"></i> UPDATE
                        </button>
                    </form>


@endsection
