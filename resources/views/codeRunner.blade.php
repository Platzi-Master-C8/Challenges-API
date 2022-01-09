Hi, docker is just running!

{{  $output  }}


<form action="{{route('run_node')}}" method="post">
    <label for="code">Code</label>
    <textarea name="code" id="code" cols="60" rows="15" placeholder="type your code here"></textarea>
    <input type="submit" value="Run">
</form>
