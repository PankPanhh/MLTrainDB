<!DOCTYPE html>
<html>
<head>
    <title>Jenkins Jobs</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<h1>Jenkins Jobs</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form action="{{ route('jenkins.trigger') }}" method="get">
    <button type="submit">Trigger MLTrainingDashboard</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Job Name</th>
            <th>Status</th>
            <th>Triggered At</th>
            <th>Log</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jobs as $job)
        <tr>
            <td>{{ $job->id }}</td>
            <td>{{ $job->job_name }}</td>
            <td>{{ $job->status }}</td>
            <td>{{ $job->triggered_at }}</td>
            <td><a href="{{ route('jenkins.log', $job->id) }}">View Log</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Git Push</h2>
<form id="gitPushForm">
    Branch: <input type="text" name="branch" value="main">
    Message: <input type="text" name="message" value="Laravel push via Jenkins">
    <button type="submit">Push</button>
</form>
<pre id="gitOutput"></pre>

<script>
document.getElementById('gitPushForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    axios.post("{{ route('jenkins.git.push') }}", Object.fromEntries(formData))
        .then(res => { document.getElementById('gitOutput').innerText = res.data.output; })
        .catch(err => { console.error(err); });
});
</script>

</body>
</html>
