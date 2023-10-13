<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;

class Provider1Service
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.provider1_base_url');
    }

    /**
     * @throws \Exception
     */
    public function getData()
    {
        $response = Http::get($this->apiBaseUrl);

        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $taskData) {
                $isDuplicate = $this->checkIsDuplicate($taskData);
                if (!$isDuplicate) {
                   $this->addNewTask($taskData);
                }
                else{
                    Dump("Duplicate task didnt add");
                }

            }
            return $data;
        }

        throw new \Exception('Cannot fetch tasks');

    }

    private function checkIsDuplicate($taskData)
    {
        return Task::where('task_name', $taskData['id'])
            ->where('duration_hours', $taskData['sure'])
            ->where('difficulty_factor', $taskData['zorluk'])
            ->first();
    }

    private function addNewTask($taskData)
    {
        $task = new Task();
        $task->task_name = $taskData['id'];
        $task->duration_hours = $taskData['sure'];
        $task->difficulty_factor = $taskData['zorluk'];
        if ($task->save()) {
            Dump("New task added");
        }
    }
}
