<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Exceptions\CouponException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CouponController extends Controller
{

    /**
     * check
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function check(Request $request)
    {
        $sessionID = $request->session()->get('id', 0);
        $reqCode = $request->input("code");
        $reqPlanID = $request->input("plan_id", 0);
        $priceId = $request->input('price_id', '');

        if (empty($reqCode)) {
            abort(500, __('Coupon cannot be empty'));
        }

        /**
         * @var Coupon $coupon
         */
        try {
            $coupon = Coupon::checkCode($reqCode, $reqPlanID, $sessionID, $priceId);
        } catch (CouponException $e) {
            abort(500, $e->getMessage());
        }

        return response([
            'data' => $coupon
        ]);
    }
}