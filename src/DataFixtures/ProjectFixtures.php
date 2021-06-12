<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setName('Website');
        $project->setDescription('My website.');
        $project->setLink('https://github.com/Aineph/Website');
        $project->setImage(
            'https://cloudinary-a.akamaihd.net/hopwork/image/upload/w_2048,c_limit,dpr_2/nq3sbveasmykhzlbyd6u.jpeg'
        );
        $manager->persist($project);
        $manager->flush();
    }
}
