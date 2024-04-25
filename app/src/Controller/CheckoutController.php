<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Ticket;
use App\Repository\BookingStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends AbstractController
{
    public function __construct(private BookingStatusRepository $bookingStatusRepository)
    {
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $booking = $this->addBooking($request, $entityManager);
        $this->addTickets($request, $entityManager, $booking);

        return new JsonResponse($booking->getId());
    }

    private function addBooking(Request $request, EntityManagerInterface $entityManager): ?Booking
    {
        $defaultBookingStatus = $this->getDefaultBookingStatus();
        if(!empty($defaultBookingStatus)) {
            $booking = new Booking();
            $booking->setMovie('sdfsdf');
            $booking->setTotal($request->get('cartTotal'));
            $booking->setStatus($this->getDefaultBookingStatus());
            $booking->setUser($this->getUser());

            $entityManager->persist($booking);
            $entityManager->flush();

            return $booking;
        }

        return null;
    }

    private function addTickets(Request $request, EntityManagerInterface $entityManager, Booking $booking): void
    {
        foreach ($request->get('cart') as $cartItem) {

            if (isset($cartItem['index']['row']) &&
                isset($cartItem['index']['col']) &&
                isset($cartItem['label']) &&
                isset($cartItem['type'])) {

                $ticket = new Ticket();
                $ticket->setSeatRow($cartItem['index']['row']);
                $ticket->setSeatCol($cartItem['index']['col']);
                $ticket->setLabel($cartItem['label']);
                $ticket->setType($cartItem['type']);
                $ticket->setBooking($booking);

                $entityManager->persist($ticket);
                $entityManager->flush();
            }

        }
    }

    private function getDefaultBookingStatus(): ?BookingStatus
    {
        $bookingStatus = $this->bookingStatusRepository->findOneBy(array('name' => 'Pending'));

        if(!empty($bookingStatus)) {
            return $bookingStatus;
        }

        return null;
    }
}
