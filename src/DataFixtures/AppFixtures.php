<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Italy!');
        $microPost1->setText('Welcome to Italy');
        $microPost1->setCreated(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welcome to France!');
        $microPost2->setText('Welcome to France');
        $microPost2->setCreated(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Borundi!');
        $microPost3->setText('Welcome to Borundi');
        $microPost3->setCreated(new DateTime());
        $manager->persist($microPost3);

        $manager->flush();
    }
}
