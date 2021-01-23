<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $message;
    public $customer_name;
    public $customer_image;
    public $order_id;

    public function __construct($order_id,$customer_name,$customer_image,$message)
    {
        $this->message=$message;
        $this->order_id=$order_id;
        $this->customer_name=$customer_name;
        $this->customer_image=$customer_image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    
    public function via($notifiable)
    {
        return ['mail','broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have a new order from '.$this->customer_name)
                    ->action('All orders', url('http://127.0.0.1:3000/dashboard/orders'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            "order_id"=>$this->order_id,
            "message"=>$this->message,
            "customer_name"=>$this->customer_name,
            "customer_image"=>$this->customer_image
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'order_id'=>$this->order_id,
            "customer_name"=>$this->customer_name,
            "customer_image"=>$this->customer_image
        ]);
    }

   
}
