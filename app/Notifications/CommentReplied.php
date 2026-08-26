<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentReplied extends Notification
{
    use Queueable;

    public $reply;

    public $parentComment;

    /**
     * Create a new notification instance.
     */
    public function __construct($reply, $parentComment)
    {
        $this->reply = $reply;
        $this->parentComment = $parentComment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = '#';
        if ($this->parentComment->commentable_type === 'App\Models\Gallery') {
            $url = route('gallery').'?highlight_comment='.$this->reply->id.'&active_id='.$this->parentComment->commentable_id;
        } elseif ($this->parentComment->commentable_type === 'App\Models\VideoInspiration') {
            $url = route('video-inspiration').'?highlight_comment='.$this->reply->id.'&active_id='.$this->parentComment->commentable_id;
        }

        return [
            'type' => 'comment_replied',
            'title' => 'Balasan Baru untuk Komentar Anda',
            'message' => $this->reply->user->name.' membalas: "'.Str::limit($this->reply->body, 100).'"',
            'url' => $url,
            'reply_id' => $this->reply->id,
            'parent_id' => $this->parentComment->id,
            'commentable_type' => $this->parentComment->commentable_type,
            'commentable_id' => $this->parentComment->commentable_id,
            'reply_body' => $this->reply->body,
            'parent_body' => $this->parentComment->body,
        ];
    }
}
