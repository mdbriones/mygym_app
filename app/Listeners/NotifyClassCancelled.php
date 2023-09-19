<?php

namespace App\Listeners;

use App\Events\ClassCancelled;
use App\Mail\ClassCancelledMail;
use App\Notifications\ClassCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotifyClassCancelled
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClassCancelled $event): void
    {
        \Log::info('Mail sending...');
        // $scheduledClass = $event->scheduledClass;
        // \Log::info($scheduledClass);

        // $members = $event->scheduledClass->members();
        $members = $event->scheduledClass->members()->get();

        $className = $event->scheduledClass->classType->name;
        $classDateTime = $event->scheduledClass->date_time;

        $details = compact('className', 'classDateTime');

        // $members->each(function ($user) use ($details){
        //     // send mail
        //     Mail::to($user)->send(new ClassCancelledMail($details));
        // });

        Notification::send($members, new ClassCancelledNotification($details));
    }
}
