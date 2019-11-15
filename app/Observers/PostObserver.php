<?php

namespace App\Observers;

use App\Post;
use App\Mail\PostEventMail;

class PostObserver
{
    public function created(Post $post)
    {
        $mail = (new PostEventMail($post))->withEvent('Создание')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $mail);
    }

    public function updated(Post $post)
    {
        $mail = (new PostEventMail($post))->withEvent('Обновление')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $mail);
    }

    public function deleted(Post $post)
    {
        $mail = (new PostEventMail($post))->withEvent('Удаление');
        $this->sendMailToAdminAboutPostEvent($post, $mail);
    }

    public function restored(Post $post)
    {
        $mail = (new PostEventMail($post))->withEvent('Восстановление')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $mail);
    }

    public function forceDeleted(Post $post)
    {
        $mail = (new PostEventMail($post))->withEvent('Фактическое удаление');
        $this->sendMailToAdminAboutPostEvent($post, $mail);
    }
    
    public function sendMailToAdminAboutPostEvent(Post $post, PostEventMail $mail)
    {
        $this->sendMail(config('app.adminEmail'), $mail);
    }
    
    public function sendMail(string $email, PostEventMail $mail)
    {
        \Mail::to($email)->send($mail);
    }
}
