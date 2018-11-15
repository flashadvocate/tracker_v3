<?php

namespace App\Notifications;

use App\Channels\DiscordMessage;
use App\Channels\WebhookChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class MemberRemoved extends Notification
{
    use Queueable;
    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $member;

    /**
     * Create a new notification instance.
     *
     * @param $member
     * @param $reason
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebhookChannel::class];
    }

    public function toWebhook()
    {
        $division = $this->member->division;

        $channel = str_slug($division->name) . '-officers';

        return (new DiscordMessage())
            ->to($channel)
            ->message("{$this->member->name} [{$this->member->clan_id}] was removed from {$division->name} by " . auth()->user()->name)
            ->success()
            ->send();
    }
}
