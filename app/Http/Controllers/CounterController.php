<?php

namespace App\Http\Controllers;

use App\Models\Counters;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    /**
     * 获取当前计数（云托管连通性测试）
     */
    public function getCount()
    {
        $counters = Counters::find(1);
        return response()->json(['code' => 0, 'data' => $counters ? $counters->count : 0]);
    }

    /**
     * 更新计数：action=inc 自增，action=clear 清零
     */
    public function updateCount(Request $request)
    {
        $action = $request->input('action');
        $counters = Counters::updateOrCreate(['id' => 1], ['count' => 0]);

        if ($action === 'inc') {
            $counters->count = $counters->count + 1;
        } elseif ($action === 'clear') {
            $counters->count = 0;
        }
        $counters->save();

        return response()->json(['code' => 0, 'data' => $counters->count]);
    }
}
