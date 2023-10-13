<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Facades\Provider1Facade;
use App\Facades\Provider2Facade;

class TaskController extends Controller
{
    const DEVELOPERS = [
            'DEV5' => ['time' => 1, 'difficulty' => 5],
            'DEV4' => ['time' => 1, 'difficulty' => 4],
            'DEV3' => ['time' => 1, 'difficulty' => 3],
            'DEV2' => ['time' => 1, 'difficulty' => 2],
            'DEV1' => ['time' => 1, 'difficulty' => 1],
        ];

    const WEEKLY_HOUR = 45;

    public function index()
    {
        $weeklyPlan = $this->createWeeklyPlan();
        if(is_null($weeklyPlan['plan']))
        {
            return "We dont have any task data\n You can use command php artisan app:get-task-data";
        }
        return view('tasks',['weeklyPlan' => $weeklyPlan]);
        //return response()->json(['weeklyPlan' => $weeklyPlan]);
    }

    private function createWeeklyPlan()
    {
        $tasks = Task::all()->toArray();
        if(count($tasks) == 0)
        {
            return ['plan' => null, 'total_weeks' => 0];
        }
        $this->sortTasks($tasks);
        //Sürdürülebilir şekilde hesap yapmakta, haftada herhangi bir boşluk kaldıysa işin büyüklüğüne bakmaksızın o haftaya o işi alarak kalan saati sonraki haftanın toplam saatinden çıkarır.
        [$developerPlan,$i] = $this->calculateAsResumable($tasks, self::DEVELOPERS);
        //Eğer sürdürülemez şekilde hesaplama yapılmasını isterseniz:
        //[$developerPlan,$i] = $this->calculateAsNotResumable($tasks, self::DEVELOPERS);


;
        return ['plan' => $developerPlan, 'total_weeks' => $i];
    }

    private function sortTasks(&$tasks)
    {
        usort($tasks, function ($a, $b) {
            return $a['difficulty_factor'] * $a['duration_hours'] < $b['difficulty_factor'] * $b['duration_hours'];
        });
    }

    private function calculateRecentScore($i, &$developer)
    {
        if ($i != 0) {
            $developer['recentScore'] += self::WEEKLY_HOUR * $developer['difficulty'];
        } else {
            $developer['recentScore'] = self::WEEKLY_HOUR * $developer['difficulty'];
        }
    }

    private function calculateTaskScore($value)
    {
        return $value['difficulty_factor'] * $value['duration_hours'];
    }

    private function calculateAsResumable($tasks, $developers)
    {
        $i = 0;
        while (count($tasks) > 0) {
            foreach ($developers as &$developer) {
                $this->calculateRecentScore($i, $developer);
            }

            foreach ($tasks as $key => $value) {
                $taskScore = $this->calculateTaskScore($value);
                foreach ($developers as $devKey => &$developer) {
                    if ($developer['recentScore'] > 0) {
                        $developerPlan[$devKey][$i][] = $value['task_name'];
                        $developer['recentScore'] = $developer['recentScore'] - $taskScore;
                        unset($tasks[$key]);
                        break;
                    }

                }

            }
            $i++;
        }
        return [$developerPlan,$i];
    }

    private function calculateAsNotResumable($tasks, $developers)
    {
        $i = 0;
        while (count($tasks) > 0) {
            foreach ($developers as &$developer) {
                $this->calculateRecentScore(0, $developer);
            }

            foreach ($tasks as $key => $value) {
                $taskScore = $this->calculateTaskScore($value);
                foreach ($developers as $devKey => &$developer) {
                    if ($developer['recentScore'] >= $taskScore) {
                        $developerPlan[$devKey][$i][] = $value['task_name'];
                        $developer['recentScore'] = $developer['recentScore'] - $taskScore;
                        unset($tasks[$key]);
                        break;
                    }

                }

            }
            $i++;
        }
        return [$developerPlan,$i];
    }

}
