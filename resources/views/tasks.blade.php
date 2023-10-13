<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Laravel ve Bootstrap CSS dosyalarını ekleyin -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Bootstrap JS dosyasını ekleyin -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
TOTAL MINIMUM WEEK COUNT: {{$weeklyPlan['total_weeks']}}
<div class="container">
    <ul class="nav nav-tabs" id="myTabs" role="tablist">
        @foreach ($weeklyPlan['plan'] as $developer => $tasks)
            <li class="nav-item">
                <a class="nav-link{{ $loop->first ? ' active' : '' }}" id="{{ $developer }}-tab" data-toggle="tab" href="#{{ $developer }}" role="tab" aria-controls="{{ $developer }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $developer }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach ($weeklyPlan['plan'] as $developer => $tasks)
            <div class="tab-pane fade{{ $loop->first ? ' show active' : '' }}" id="{{ $developer }}" role="tabpanel" aria-labelledby="{{ $developer }}-tab">
                <ul>
                    @foreach ($tasks as $week => $taskList)
                        <li><strong>Week {{ $week + 1 }} Tasks:</strong></li>
                        <ul>
                            @foreach ($taskList as $task)
                                <li>{{ $task }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
