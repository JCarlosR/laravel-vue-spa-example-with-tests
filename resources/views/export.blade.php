<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exported tasks by date range</title>
</head>
<body>
<h1>Exported tasks</h1>
<ul>
    @foreach ($tasksByDate as $date => $data)
    <li>
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Total time:</strong> {{ $data['total'] }} minutes</p>
        <p><strong>Notes:</strong></p>
        <ul>
            @foreach ($data['tasks'] as $task)
                <li><u>{{ $task->title }}.-</u> {{ $task->description }}.</li>
            @endforeach
        </ul>
    </li>
    @endforeach
</ul>
</body>
</html>