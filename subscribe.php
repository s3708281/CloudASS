<?php
    session_start();
    require 'php/vendor/autoload.php';
    use Google\Cloud\PubSub\PubSubClient;
    use \google\appengine\api\mail\Message;

/**
 * Publishes a message for a Pub/Sub topic.
 *
 * @param string $projectId  The Google project ID.
 * @param string $topicName  The Pub/Sub topic name.
 * @param string $message  The message to publish.
 */
    
    $projectId='cloudlab3-249301';
    $subscriptionName='mytopic';
    $pubsub = new PubSubClient([
                               'projectId' => $projectId,
                               ]);
    $subscription = $pubsub->subscription($subscriptionName);
    foreach ($subscription->pull() as $messages) {
        $msg=$messages->data();
        // Acknowledge the Pub/Sub message has been received, so it will not be pulled multiple times.
        $subscription->acknowledge($messages);
    }
    
        $subject='Testing';
        $email='bhavi.smehta92@gmail.com';
        $message = new Message();
        $message->setSender("bhavi.smehta@gmail.com");
        $message->addTo($email);
        $message->setSubject($subject);
        $message->setTextBody($msg);
        $message->send();
        header("location:main.php");
?>
<html>
<header>
Subscribe Page
</header>
</html>
