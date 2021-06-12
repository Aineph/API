<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class MessageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $message = new Message();
        $message->setSenderName('User');
        $message->setSenderEmail('user@test.fr');
        $message->setObject('User message.');
        $message->setContent('This is a message from the user.');
        $manager->persist($message);
        $manager->flush();
    }
}
