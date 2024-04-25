<?php

namespace App\DataFixtures;

use App\Entity\BookingStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookingStatusFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = [
            'Pending',
            'On-hold',
            'Failed',
            'Processing',
            'Cancelled',
            'Completed',
            'Refunded'
        ];

        foreach ($names as $name) {
            $bookingStatus = new BookingStatus();
            $bookingStatus->setName($name);
            $manager->persist($bookingStatus);
        }

        $manager->flush();
    }
}
