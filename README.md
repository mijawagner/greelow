Exercise: 

Nested Comments System with Notifications
Problem Description
Develop an API in Laravel to manage comments on a post, supporting nested comments (replies to specific comments) up to several levels (parent, child, grandchild, etc.). Additionally, the system should send notifications to the administrator whenever a new comment is added. These notifications should be sent via various channels (such as email and SMS) with the flexibility to scale to additional notification methods, such as real-time desktop notifications.

Functional Requirements
Nested Comments API:

The API should allow:
Creating, editing, listing, and deleting comments.
Replying to specific comments, supporting multiple levels of nesting.
Each comment should include:
Comment text.
The parent comment ID (if it’s a reply).
Author information.
Timestamp.
Notification System:

Implement a system that notifies the administrator whenever a new comment is received.
Notifications should be sent via:
Email: Implement email notifications using Laravel’s Mail feature.
SMS: Implement SMS notifications using a simulated service (e.g., logging to a file).
Scalability: The notification architecture should be flexible, allowing the addition of new notification channels, such as real-time desktop notifications (e.g., using WebSockets or Pusher).
Seeder for Sample Comments:

Create a seeder to populate the database with 100 sample comments in a hierarchical structure (parents, children, grandchildren, etc.).
Ensure the comments structure supports multiple branches with nested comments (some up to four levels deep).
Technical Requirements
Model and Eloquent Relationships:

Define the Comment model with the necessary relationships to support nested comments.
Scalable Notification System:

Create a notification service that accepts the comment and channel as inputs, allowing new channels to be added in the future.
Seeder:

Create a seeder file that generates 10000 comments with a hierarchical structure.
Include at least three levels of depth in some comment branches.

Unit Tests: Include unit tests for the comments and notifications system.

Real-Time Notifications: Describe how to implement for the administrator using Pusher Notifications.


.env
Complete these used values

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=        
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=i
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}" 

MAIL_ADMIN=


Run the migration files to create table
and seeders to insert Data ( User, Post, Comment )

Here my working environment
https://ejercicio.michaelwagner.com.ar

For instance
GET https://ejercicio.michaelwagner.com.ar/api/posts


Pusher

To implement real-time notifications for the administrator using Pusher in Laravel, you can follow these steps. This setup will send real-time notifications when a new comment is created.

Overview of Steps
Set up Pusher Account and Credentials
Install Pusher and Configure Laravel Broadcast
Define the Event for Broadcasting
Broadcast the Event in the Controller
Listen for Notifications on the Client Side

Step 1: Set up Pusher Account and Credentials
Go to Pusher pusher.com and create a free account.

Create a new Pusher Channels app.

App ID
Key
Secret
Cluster

Add these credentials to your .env file:

env
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_pusher_cluster

BROADCAST_DRIVER=pusher


Step 2: Install Pusher and Configure Laravel Broadcast

Install the Pusher PHP SDK:

composer require pusher/pusher-php-server

Ensure that the pusher configuration in config/broadcasting.php uses the Pusher credentials from .env:

php
Copy code
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
    ],
],

Step 3: Define the Event for Broadcasting

Run the Artisan command to create a new event class. Here, we’ll call it NewCommentEvent:

php artisan make:event NewCommentEvent

In NewCommentEvent.php, define the properties for the comment data and configure it to broadcast.

// app/Events/NewCommentEvent.php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewCommentEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return new Channel('admin-notifications');
    }

    public function broadcastAs()
    {
        return 'new-comment';
    }
}


Step 4: Broadcast the Event in the Controller
In the CommentController, dispatch the NewCommentEvent when a new comment is created:

// app/Http/Controllers/CommentController.php

...
        // Broadcast the new comment event
        broadcast(new NewCommentEvent($comment))->toOthers();
...

Step 5: Set Up the Client Side to Listen for Events
To listen for real-time notifications on the client side, you’ll use Laravel Echo with Pusher.

Install Laravel Echo and Pusher JavaScript Library:

npm install --save laravel-echo pusher-js
Configure Laravel Echo:

Add the following configuration to your JavaScript ( resources/js/bootstrap.js ):

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
});

Listen for the Event on the Client Side:

In your JavaScript file, listen for the new-comment event on the admin-notifications channel.

 ( resources/js/app.js )
window.Echo.channel('admin-notifications')
    .listen('.new-comment', (event) => {
        console.log('New comment notification:', event.comment);
        // Display notification to the admin, e.g., using a toast or modal
        alert(`New comment by ${event.comment.author}: ${event.comment.text}`);
    });
.listen('.new-comment'): 


Compile JavaScript Assets:

Run the following command to compile your JavaScript assets:

npm run dev

This will start the Vite development server, which will compile and serve your JavaScript assets.

php artisan serve

Start your Echo server if needed.

Open your app in a browser where the administrator can listen for notifications.

Create a new comment by submitting a POST request to /api/posts/{post}/comments.

