<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

/**
 * 业主认证接口
 *
 * 小程序通过 wx.cloud.callContainer 调用时，微信云托管会自动在请求头中
 * 注入 X-WX-OPENID（当前用户身份），后端以此区分用户，无需维护登录态。
 * 公网域名访问没有该请求头，接口返回 401。
 */
class OwnerController extends Controller
{
    private function openid(Request $request)
    {
        return $request->header('x-wx-openid');
    }

    /**
     * 输出给前端的字段（与小程序端数据结构保持一致）
     */
    private function format(Owner $owner)
    {
        return [
            'name'        => $owner->name,
            'phone'       => $owner->phone,
            'houseNo'     => $owner->house_no,
            'idTail'      => $owner->id_tail,
            'memberNo'    => $owner->member_no,
            'certifiedAt' => optional($owner->created_at)->format('Y-m-d'),
        ];
    }

    /**
     * GET /api/owner/profile —— 查询当前用户认证信息
     */
    public function profile(Request $request)
    {
        $openid = $this->openid($request);
        if (!$openid) {
            return response()->json(['code' => 1, 'message' => '缺少用户身份'], 401);
        }

        $owner = Owner::where('openid', $openid)->first();

        return response()->json(['code' => 0, 'data' => $owner ? $this->format($owner) : null]);
    }

    /**
     * POST /api/owner/apply —— 提交/更新业主认证
     * body: { name, phone, houseNo, idTail }
     */
    public function apply(Request $request)
    {
        $openid = $this->openid($request);
        if (!$openid) {
            return response()->json(['code' => 1, 'message' => '缺少用户身份'], 401);
        }

        $validatedData = $request->validate([
            'name'    => ['required', 'string', 'max:20'],
            'phone'   => ['required', 'regex:/^1\d{10}$/'],
            'houseNo' => ['required', 'string', 'max:50'],
            'idTail'  => ['required', 'regex:/^\d{4}$/'],
        ], [
            'name.required'    => '请输入姓名',
            'phone.required'   => '请输入手机号',
            'phone.regex'      => '手机号格式不正确',
            'houseNo.required' => '请输入楼栋房号',
            'idTail.required'  => '请输入身份证后4位',
            'idTail.regex'     => '身份证后4位格式不正确',
        ]);

        // 同一 openid 再次提交即为修改信息
        $owner = Owner::updateOrCreate(
            ['openid' => $openid],
            [
                'name'     => $validatedData['name'],
                'phone'    => $validatedData['phone'],
                'house_no' => $validatedData['houseNo'],
                'id_tail'  => $validatedData['idTail'],
                'status'   => 1,
            ]
        );

        // 生成业主编号：FDD + 6位自增ID
        if (!$owner->member_no) {
            $owner->member_no = 'FDD' . str_pad((string) $owner->id, 6, '0', STR_PAD_LEFT);
            $owner->save();
        }

        return response()->json(['code' => 0, 'data' => $this->format($owner)]);
    }

    /**
     * POST /api/owner/cancel —— 退出认证（删除记录）
     */
    public function cancel(Request $request)
    {
        $openid = $this->openid($request);
        if (!$openid) {
            return response()->json(['code' => 1, 'message' => '缺少用户身份'], 401);
        }

        Owner::where('openid', $openid)->delete();

        return response()->json(['code' => 0, 'data' => null]);
    }
}
