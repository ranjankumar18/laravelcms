@extends('layouts.app')

@section('content')
<div class="card card-default">
  <div class="card-header">
    {{ isset($tags)  ? 'Edit Tags' : 'Create Tags' }}
  </div>
  <div class="card-body">
   @include('partials.errors')
    <form action="{{ isset($tags) ? route('tags.update', $tags->id) : route('tags.store') }}" method="POST">
      @csrf
      @if(isset($tags))
        @method('PUT')
      @endif
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" class="form-control" name="name" value="{{ isset($tags) ? $tags->name : '' }}">
      </div>
      <div class="form-group">
        <button class="btn btn-success">
          {{ isset($tags) ? 'Update Tags': 'Add Tags' }}
        </button>
      </div>
    </form>
  </div>
</div>
@endsection