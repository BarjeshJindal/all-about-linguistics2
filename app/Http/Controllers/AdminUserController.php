<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NaatiSubscriptionPlan;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{

    public function listUsers()
    {
        $users = DB::table('users')
            ->leftJoin('naati_subscription_plans', 'users.subscription_id', '=', 'naati_subscription_plans.id')
            ->select(
                'users.*',
                'naati_subscription_plans.plan_type'
            )
            ->get();

        return view('admin.subscriptions.usersList', compact('users'));
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $plans = NaatiSubscriptionPlan::all();
        return view('admin.subscriptions.assignSubscriptions', compact('user', 'plans'));
       
    }

    
    public function assignSubscription(Request $request, $id)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:naati_subscription_plans,id',
        ]);

        $user = User::findOrFail($id);
        $currentDate = Carbon::now()->toDateString();

        // Check if the user has an active subscription
        $activeSubscription = UserSubscription::where('user_id', $id)
            ->where('end_date', '>=', $currentDate)
            ->first();

        if ($activeSubscription) {
            return redirect()->back()->with('error', 'This user already has an active subscription.');
        }

        // Proceed to assign new subscription
        $plan = NaatiSubscriptionPlan::findOrFail($request->subscription_plan_id);
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($plan->duration_days);

        UserSubscription::create([
            'user_id' => $id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);

        $user->subscription_id = $plan->id;
        $user->save();

        return redirect()->route('admin.users.list')->with('success', 'Subscription assigned successfully.');
    }

}
