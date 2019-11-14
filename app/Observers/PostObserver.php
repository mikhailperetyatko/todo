<?php

namespace App\Observers;

use App\Post;
use App\Mail\PostEventMail;

class PostObserver
{
    public function created(Post $post)
    {
        $values = (new PostObserverValues)->withEvent('Создание')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $values);
    }

    public function updated(Post $post)
    {
        $values = (new PostObserverValues)->withEvent('Обновление')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $values);
    }

    public function deleted(Post $post)
    {
        $values = (new PostObserverValues)->withEvent('Удаление');
        $this->sendMailToAdminAboutPostEvent($post, $values);
    }

    public function restored(Post $post)
    {
        $values = (new PostObserverValues)->withEvent('Восстановление')->withLink();
        $this->sendMailToAdminAboutPostEvent($post, $values);
    }

    public function forceDeleted(Post $post)
    {
        $values = (new PostObserverValues)->withEvent('Фактическое удаление');
        $this->sendMailToAdminAboutPostEvent($post, $values);
    }
    
    public function sendMailToAdminAboutPostEvent(Post $post, PostObserverValues $values)
    {
        $this->sendMail(config('app.adminEmail'), new PostEventMail($post, $values));
    }
    
    public function sendMail(string $email, PostEventMail $mail)
    {
        \Mail::to($email)->queue($mail);
    }
}
