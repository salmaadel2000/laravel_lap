@extends('layouts.app')

@section('content')
<div class="container" style="background-color: lightblue;">
        
        <table class="table mt-3" style="border-collapse: collapse; width: 100%;">
            <thead style="background-color: #343a40; color: white;">
                <tr>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">ID</th> 
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Name</th>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Body</th> 
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Posted By</th>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Image</th>
                    <th colspan="3" style="padding: 8px; border: 1px solid #dee2e6;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $post->id }}</td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $post->title }}</td> 
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $post->body }}</td> 
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $post->user->name }}</td>   
                        <td style="padding: 8px; border: 1px solid #dee2e6;">
                            <img src="{{ asset('images/' . $post->image) }}" alt="not exist" style="max-width: 100px;">
                        </td>    
                        <td style="padding: 8px; border: 1px solid #dee2e6;"><a href="/posts/{{ $post->id }}" class="btn btn-success">Show</a></td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;"><a href="/posts/{{ $post->id }}/edit" class="btn btn-warning">Edit</a></td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">
                            <form action="/posts/{{ $post->id }}" method="POST">
                                @csrf
                                @method('delete')   
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="/posts/create" class="btn btn-primary mt-5" style="width:100%" >create new post</a>
        <div class="d-flex justify-content-center" style="margin-top: 10px;">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
