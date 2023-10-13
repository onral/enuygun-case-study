<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;

class Provider2Service
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.provider2_base_url');
    }

    public function getData()
    {
        $response = Http::get($this->apiBaseUrl);

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $businessTasks)
            {
                foreach ($businessTasks as $name => $taskData)
                {
                    $isDuplicate = $this->checkIsDuplicate($name, $taskData);
                    if (!$isDuplicate) {
                        $this->addNewTask($name, $taskData);
                    }
                    else{
                        Dump("Duplicate task didnt add");
                    }
                }
            }
            return $data;
        }

        throw new \Exception('Cannot fetch tasks');

    }

    private function checkIsDuplicate($name, $taskData)
    {
        return Task::where('task_name', $name)
            ->where('duration_hours', $taskData['estimated_duration'])
            ->where('difficulty_factor', $taskData['level'])
            ->first();
    }

    private function addNewTask($name, $taskData)
    {
        $task = new Task();
        $task->task_name = $name;
        $task->duration_hours = $taskData['estimated_duration'];
        $task->difficulty_factor = $taskData['level'];
        if ($task->save()) {
            Dump("New task added");
        }
    }
}
