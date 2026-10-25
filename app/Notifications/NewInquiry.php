<?php

namespace App\Notifications;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInquiry extends Notification
{
    use Queueable;

    public function __construct(public Inquiry $inquiry) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable instanceof User ? ['database'] : ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $i = $this->inquiry;
        $reference = $i->reference();

        return (new MailMessage)
            ->subject("New quote request {$reference} – {$i->name}".($i->company ? " ({$i->company})" : ''))
            ->greeting('New quote request')
            ->line("**Reference:** {$reference}")
            ->line("**Name:** {$i->name}")
            ->line('**Company:** '.($i->company ?: '—'))
            ->line("**Phone:** {$i->phone}")
            ->line("**Email:** {$i->email}")
            ->line('**Service:** '.($i->service?->title_en ?: '—'))
            ->line('**Facility:** '.($i->sector?->name_en ?: '—'))
            ->line('**Location / area:** '.($i->location ?: '—').' / '.($i->area ?: '—'))
            ->line('**Message:**')
            ->line($i->message)
            ->action('Open in admin panel', InquiryResource::getUrl('view', ['record' => $i]))
            ->replyTo($i->email, $i->name);
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('admin.notifications.new_inquiry', ['name' => $this->inquiry->name], $notifiable->locale ?? 'ar'))
            ->body($this->inquiry->company)
            ->icon('heroicon-o-inbox-arrow-down')
            ->iconColor('danger')
            ->actions([
                Action::make('view')
                    ->label(__('admin.actions.open', locale: $notifiable->locale ?? 'ar'))
                    ->url(InquiryResource::getUrl('view', ['record' => $this->inquiry])),
            ])
            ->getDatabaseMessage();
    }
}
