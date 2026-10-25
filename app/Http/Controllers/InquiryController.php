<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Http\Resources\SectorResource;
use App\Http\Resources\ServiceResource;
use App\Models\Inquiry;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewInquiry;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('Contact', [
            'services' => ServiceResource::collection(Service::query()->published()->get())->resolve(),
            'sectors' => SectorResource::collection(Sector::query()->published()->get())->resolve(),
            'preselect' => ['service' => $request->string('service')->toString() ?: null],
            'timelines' => StoreInquiryRequest::TIMELINES,
            'meta' => Seo::make(__('site.contact.page_title'), __('site.contact.page_subtitle')),
        ]);
    }

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        $inquiry = Inquiry::query()->create([
            ...$request->safe()->except('website'),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        $notifiable = Setting::get('notification_email');
        if ($notifiable) {
            Notification::route('mail', $notifiable)->notify(new NewInquiry($inquiry));
        }
        Notification::send(User::all(), new NewInquiry($inquiry));

        return Inertia::flash('inquiry', ['reference' => $inquiry->reference()])->back();
    }
}
