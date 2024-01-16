@extends('auth.diagnose_home')
@section('title', 'Diagnose B.I.')

@section('conteudo')

    <div style="height: 200%" class="row">
        <div style="height: 160%" class="col-md-12">
            <iframe width="100%" height="100%" src="https://app.powerbi.com/view?r=eyJrIjoiOWVjYzMwMjYtM2E1YS00YzRkLWI3ZTQtYzhhNmQwNWNiM2UyIiwidCI6ImQ3ODE2YWZmLTVhYjUtNGZiNS04MDA3LWQ0YzY4ZTgyNWQzMyJ9" frameborder="0" allowFullScreen="true"></iframe>
        </div>
    </div>
@endsection