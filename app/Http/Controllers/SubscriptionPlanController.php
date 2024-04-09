<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubscriptionPlanController extends Controller
{

    public function index() {
        return Inertia::render('Admin/Subscriptions/Index', [
            'subscriptions' => SubscriptionPlan::query()->get()
        ]);
    }

    public function show(SubscriptionPlan $subscriptionPlan) {
        return Inertia::render('Admin/Subscriptions/{$id}/Index', [
            'subscription' => SubscriptionPlan::find($subscriptionPlan)
        ]);
    }

    public function create() {
        return Inertia::render('Admin/Subscriptions/Create', [
//            'subscriptions' => SubscriptionPlan::query()->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', SubscriptionPlan::class);

        // Your code for creating a subscription plan
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1024',
            'price_id' => 'unique:subscription_plans|required|max:50',
            'product_id' => 'required|string|max:50',
            'image_id' => 'integer|nullable',
        ]);

        $subscriptionPlan = SubscriptionPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price_id' => $request->price_id,
            'product_id' => $request->product_id,
            'image_id' => $request->image_id,
        ]);

        // Use this route to return
        // the user to the new show page.

        return redirect()->route('subscription-plans.show', $subscriptionPlan)->with('success', 'Subscription Plan Created Successfully');


    }

    public function edit(SubscriptionPlan $subscriptionPlan) {
        return Inertia::render('Admin/Subscriptions/{$id}/Edit', [
            'subscription' => SubscriptionPlan::find($subscriptionPlan)
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $this->authorize('update', $subscriptionPlan);

        // Your code for updating a subscription plan
        $id = $request->id;
        $subscriptionPlan = SubscriptionPlan::find($id);

        // validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1024',
            'price_id' => 'unique:subscription_plans|required|string|max:50',
            'product_id' => 'required|string|max:50',
            'image_id' => 'integer|nullable',
        ]);

        // update the show notes
        $subscriptionPlan->name = $request->name;
        $subscriptionPlan->description = $request->description;
        $subscriptionPlan->price_id = $request->price_id;
        $subscriptionPlan->product_id = $request->product_id;
        $subscriptionPlan->image_id = $request->image_id;
        $subscriptionPlan->save();
        sleep(1);

        return redirect(route('subscription-plans.show', [$subscriptionPlan->id]))->with('success', 'Subscription Plan Updated Successfully');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $this->authorize('delete', $subscriptionPlan);

        // Your code for deleting a subscription plan

        $subscriptionPlan->delete();
        sleep(1);

        // redirect
        return redirect()->route('subscription-plans.index')->with('message', 'Subscription Plan Deleted Successfully');

    }

}
