@extends('admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Posts</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-6">
            @if (!empty($post))
            <h2>Edit</h2>
            {!! Form::model($post, [
                'method' => 'PATCH',
                'route' => ['admin.posts.update', $post->id],
                'files' => true
             ]) !!}
            @else
                <h2>Add</h2>
                {!! Form::model($post = new App\Post, ['route' => ['admin.posts.store'], 'files' => true]) !!}
            @endif

            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('category_id', 'Category') !!}
                {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('ibsn', 'IBSN 10') !!}
                {!! Form::text('ibsn', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('desc', 'Short Description') !!}
                {!! Form::textarea('desc', null, ['class' => 'form-control']) !!}
            </div>


            <div class="form-group">
                {!! Form::label('content', 'Content') !!}
                {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('publisher', 'Publisher') !!}
                {!! Form::text('publisher', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('release_date', 'Release Date') !!}
                {!! Form::text('release_date', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('pages', 'Pages') !!}
                {!! Form::text('pages', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('image', 'Image') !!}
                {!! Form::text('image', null, ['class' => 'form-control']) !!}
            </div>

                <div class="form-group">
                    {!! Form::label('preview', 'Preview Link') !!}
                    {!! Form::text('preview', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('author', 'Author') !!}
                    {!! Form::text('author', null, ['class' => 'form-control']) !!}
                </div>

            <div class="form-group">
                {!! Form::label('tag_list', 'Tags') !!}
                {!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
            </div>


            <div class="form-group">
                {!! Form::label('feature', 'Feature') !!}
                {!! Form::checkbox('feature', null, null) !!}
            </div>

            <div class="form-group">
                {!! Form::label('recent', 'Recent') !!}
                {!! Form::checkbox('recent', null, null) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', 'Publish') !!}
                {!! Form::checkbox('status', null, null) !!}
            </div>



            <div class="form-group">
                {!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
            </div>

            {!! Form::close() !!}

            @include('admin.list')

        </div>
    </div>
@stop

@section('footer')
    <script>
        $('#tag_list').select2({
            placeholder : 'choose or add new tag',
            tags : true //allow to add new tag which not in list.
        });
    </script>
@endsection