Hi, docker is just running!


{{-- @endsection --}}
<form action="{{route('run_node')}}" method="post">
    @csrf
    <label for="code">Code</label>
    <textarea name="code" id="code" cols="60" rows="15" placeholder="type your code here">{{$template}}</textarea>
    <input type="submit" value="Run">
</form>
