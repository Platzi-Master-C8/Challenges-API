<style>
    html {
        background-color: #2d2d2d;
        color: white;
    }
</style>
{{-- @endsection --}}

<h1>Gethired Code Playground</h1>

<p>Write a function that will return the sum of two numbers</p>
<form action="{{route('run_node')}}" method="post">
    @csrf

    <input type="hidden" name="challenge_id" value="01">
    <input type="hidden" name="challenge_name" value="cats">
    <label for="code"></label>
    @if(isset($json) && $json->testResults[0]->assertionResults[0]->status == 'passed' )
        <textarea name="code" id="code" cols="60" rows="15" placeholder="type your code here"
                  disabled>{{$template}}</textarea>

    @else
        <textarea name="code" id="code" cols="60" rows="15" placeholder="type your code here">{{$template}}</textarea>
        <input type="submit" value="Run">

    @endif


</form>

@php
    echo '<pre>';

@endphp

@if(isset($json))
    <h4> Status: {{$json->testResults[0]->assertionResults[0]->status}}</h4>
@endif

