<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Photo;
use App\Entity\Review;
use App\Entity\Booking;
use App\Entity\Property;
use App\Entity\Availability;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create users
        $users = [];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setPassword('$2y$13$DsWz0r/6LXVpUW028gC3iePf8bjWBxUz9gsevV4BdrnUDvs2l1JZu');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        // Create properties
        $properties = [];
        
        for ($i = 0; $i < 10; $i++) {
            $property = new Property();
            $property->setName($faker->word);
            $property->setDescription($faker->text);
            $property->setAddress($faker->address);
            $property->setPricePerNight($faker->randomFloat(2, 20, 200));
            $property->setOwner($faker->randomElement($users));
            $manager->persist($property);
            $properties[] = $property;
        }

        // Create photos
        foreach ($properties as $property) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                $photo = new Photo();
                $photo->setUrl($faker->imageUrl);
                $photo->setProperty($property);
                $manager->persist($photo);
            }
        }

        // Create availabilities
        foreach ($properties as $property) {
            for ($i = 0; $i < 5; $i++) {
                $availability = new Availability();
                $availability->setStartDate($faker->dateTimeBetween('now', '+1 month'));
                $availability->setEndDate($faker->dateTimeBetween('+1 month', '+2 months'));
                $availability->setProperty($property);
                $manager->persist($availability);
            }
        }

        // Create bookings
        foreach ($properties as $property) {
            for ($i = 0; $i < 5; $i++) {
                $booking = new Booking();
                $booking->setProperty($property);
                $booking->setTenant($faker->randomElement($users));
                $booking->setStartDate($faker->dateTimeBetween('now', '+1 month'));
                $booking->setEndDate($faker->dateTimeBetween('+1 month', '+2 months'));
                $booking->setStatus($faker->randomElement(['confirmed', 'pending', 'cancelled']));
                $manager->persist($booking);
            }
        }

        // Create reviews
        foreach ($properties as $property) {
            for ($i = 0; $i < 5; $i++) {
                $review = new Review();
                $review->setProperty($property);
                $review->setTenant($faker->randomElement($users));
                $review->setRating($faker->numberBetween(1, 5));
                $review->setComment($faker->text);
                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}
