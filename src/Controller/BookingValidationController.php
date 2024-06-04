<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingValidationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManagerInterface)
    {}

    public function __invoke(Booking $booking)
    {

        if($booking->getStatus() !== 'confirmed') {
            $booking->setStatus('confirmed');
        }

        $this->entityManagerInterface->flush();

        return new JsonResponse(['status' => 'Booking confirmed']);
    }
}
