<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/booking')]
class BookingController extends AbstractController
{
    #[Route('/', name: 'app_booking_index', methods: ['GET'])]
    public function booking_index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        $user=$this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            //adding user id 
            if($user){
                $booking->setUserId($user->getID());
            }
            else
            $booking->setUserId(0);

            //check if room is available on this date;
            $rooms=$bookingRepository->createQueryBuilder('q')
            ->andWhere('q.room = :val')
            ->setParameter('val', $booking->GetRoom())
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
            $free=true;
            foreach ($rooms as $r) {
                if($r->getBookedUntil()>$booking->GetBookedFrom()){$free=false;break;}
            }
            if($free == true && $booking->GetBookedFrom()<$booking->GetBookedUntil())
            {$bookingRepository->save($booking, true);
                return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
               }
               //if dates are messed up somehow
               else if($booking->GetBookedFrom()>$booking->GetBookedUntil()){
                $this->addFlash('error', 'Selected dates not valid');
               }
               else $this->addFlash('error', 'Room unavailable on selected dates!');

        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
        
    }

    #[Route('/{id}/edit', name: 'app_booking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->save($booking, true);

            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_booking_delete', methods: ['POST'])]
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking, true);
        }

        return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
