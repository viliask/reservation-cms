<?php

namespace App\Controller\Website;

use App\Controller\Traits\CommonTrait;
use App\Entity\Event;
use App\Entity\EventTranslation;
use App\Entity\ReservationSettings;
use App\Entity\Room;
use App\Form\Type\EventType;
use App\Form\Type\ReservationType;
use App\Repository\EventRepository;
use App\Repository\ReservationSettingsRepository;
use App\Repository\RoomRepository;
use DateTime;
use DateTimeImmutable;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pokoje")
 */
class RoomController extends AbstractController
{
    use CommonTrait;

    const ROUTE_PREFIX = 'pokoje';

    /**
     * @Route("/potwierdzenie", name="room_confirmation", methods={"GET"})
     */
    public function confirmation(): Response
    {
        return $this->render('room/confirmation.html.twig');
    }

    /**
     * @Route("/", name="rooms_show", methods="GET")
     */
    public function showRooms(RoomRepository $repository, MediaManagerInterface $mediaManager): Response
    {
        $rooms = $repository->findAll();
        $media = [];

        /* @var $room Room */
        foreach ($rooms as $room) {
            $media[$room->getId()] = $this->getMedia($room, $mediaManager);
        }

        return $this->render('room/rooms.html.twig', [
            'rooms' => $rooms,
            'media' => $media,
        ]);
    }

    /**
     * @Route("/{slug}/{checkIn}/{checkOut}", name="room_show_dates", methods={"GET", "POST"})
     */
    public function showWithDates(
        Room $room,
        Request $request,
        string $checkIn,
        string $checkOut,
        MediaManagerInterface $mediaManager,
        Swift_Mailer $mailer
    ): Response {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);
        $checkInDate      = new DateTimeImmutable($checkIn);
        $checkOutDate     = new DateTimeImmutable($checkOut);
        $guests           = $request->query->get('guests');

        $availabilityForm->get('checkInDate')->setData($checkInDate);
        $availabilityForm->get('checkOutDate')->setData($checkOutDate);
        $availabilityForm->get('guests')->setData($guests);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            return $this->processForm($event, $room, $mailer);
        }

        $params = $this->createParams($room, $eventForm, $availabilityForm, $mediaManager);
        $params['checked'] = '';

        return $this->render('room/show.html.twig', $params);
    }

    /**
     * @Route("/{slug}", name="room_show", methods={"GET", "POST"})
     */
    public function show(Room $room, Request $request, MediaManagerInterface $mediaManager, \Swift_Mailer $mailer): Response
    {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            return $this->processForm($event, $room, $mailer);
        }

        $params = $this->createParams($room, $eventForm, $availabilityForm, $mediaManager);

        return $this->render('room/show.html.twig', $params);
    }

    private function createParams(
        Room $room,
        FormInterface $eventForm,
        FormInterface $availabilityForm,
        MediaManagerInterface $mediaManager
    ): array {
        return
            [
                'room'             => $room,
                'form'             => $eventForm->createView(),
                'availabilityForm' => $availabilityForm->createView(),
                'maxGuests'        => $room->getMaxGuests(),
                'stepsAmount'      => $room->getStepsAmount(),
            ] + $this->getMedia($room, $mediaManager);
    }

    private function processForm(Event $event, Room $room, Swift_Mailer $mailer): RedirectResponse
    {
        $event->setLocale('pl');
        $event->setStatus('draft');
        $event->addRoom($room);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist(new EventTranslation($event, 'en'));
        $entityManager->persist(new EventTranslation($event, 'pl'));
        $entityManager->persist($event);
        $entityManager->flush();

        $this->sendConfirmationMail($event, $mailer, 'hello@pokojebeata.pl', $event->getMail());
        $this->sendConfirmationMail($event, $mailer, $event->getMail(), 'hello@pokojebeata.pl');

        return $this->redirectToRoute('room_confirmation');
    }

    /**
     * @Route("/availability/{id}/{checkIn}/{checkOut}/{guests}", name="xhr_room_availability", options={"expose"=true}, methods="GET")
     */
    public function checkRoomAvailability(
        Room $room,
        string $checkIn,
        string $checkOut,
        string $guests,
        EventRepository $eventRepository,
        ReservationSettingsRepository $settingsRepository
    ): JsonResponse {
        $availableRoom  = $eventRepository->findAvailableRooms($checkIn, $checkOut, $room->getId());
        $discountParams = [];
        $settingsParams = [];
        $guests = intval($guests);
        $finalPrice = null;
        $tempPriceField = null;

        if ($availableRoom) {
            /** @var Room $roomObject */
            $roomObject = $availableRoom[0];
            if ($roomObject->getName() === $room->getName()) {
                $status         = true;
                $discountParams = $this->findPromoOffer($roomObject, $checkIn, $checkOut);
                $discountParams += $this->addPriceParams(
                    $room,
                    $checkIn,
                    $checkOut,
                    $guests,
                    $discountParams['discount'],
                    $settingsRepository
                );
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        return $this->json(
            [
                'status' => $status
            ] + $discountParams
        );
    }

    private function findPromoOffer(Room $room, string $checkIn, string $checkOut): array
    {
        $checkInDate  = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        $daysBetween  = date_diff($checkInDate, $checkOutDate)->d;
        $offers       = $room->getPromoOffers();
        $fairDiscount = 0;
        $discountName = null;

        foreach ($offers as $offer) {
            $startDate = $offer->getStartDate();
            $endDate   = $offer->getEndDate();
            if ($checkInDate >= $startDate && $checkInDate <= $endDate && $checkOutDate <= $endDate && $startDate <= $checkOutDate) {
                $discount = $offer->getDiscount();
                if ($discount >= $fairDiscount && $daysBetween >= $offer->getMinDays()) {
                    $fairDiscount = $discount;
                    $discountName = $offer->getName();
                }
            }
        }

        return ['discount' => $fairDiscount, 'discountName' => $discountName];
    }

    private function sendConfirmationMail(Event $event, Swift_Mailer $mailer, string $sender, string $receiver): void
    {
        $message = new Swift_Message('Zapytanie o rezerwację - pokojebeata.pl');
        $message->setFrom($sender);
        $message->setTo($receiver);
        $message->setBody(
            '<html>'.
            '<head></head>'.
            '<body>'.
            'Dziękujemy '.$event->getFirstName().','.
            '<p>otrzymaliśmy Twoje zapytanie o rezerwację. Nasz obiekt jest dostępny również na innych portalach, dlatego musimy sprawdzić czy możesz go zarezerwować w podanym terminie.</p>'.
            '<p>Odpowiemy najszybciej jak to będzie możliwe - telefonicznie lub mailowo.'.
            '<p>Pozdrawiamy</p>'.
            '<img src="'.
            $message->embed(Swift_Image::fromPath('https://pokojebeata.pl/web/images/pokojebeata-logo.webp')).
            '" height="70px" alt="pokojebeata.pl" title="pokojebeata.pl" />'.
            '</body>'.
            '</html>',
            'text/html'
        );

        $mailer->send($message);
    }

    private function addPriceParams(
        Room $room,
        string $checkIn,
        string $checkOut,
        string $guests,
        int $promoOfferDiscount,
        ReservationSettingsRepository $settingsRepository
    ): array {
        $seasonPrice = null;
        $stepsParams = [];
        $checkInDate  = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        $daysOfVisit  = $checkOutDate->diff($checkInDate)->days;

        if ($room->getStepsAmount() > 0 && $room->getMaxGuests() > $guests) {
            $multiplier                  = $room->getMaxGuests() - $guests;
            $finalStepsDiscount          = $room->getStepsDiscount() * $multiplier;
            $stepsParams['stepsContent'] = 'W zależności od liczby osób '.$finalStepsDiscount.'%';
        } else {
            $finalStepsDiscount = 0;
        }

        /** @var ReservationSettings $settings*/
        $settings = $settingsRepository->isEnabled()[0];
        if ($settings) {
            $summerDiscount = (100 - $settings->getPriceModifier()) / 100;
            $startDate      = (new DateTime())
                ->setDate(0000, intval($checkInDate->format('m')), intval($checkInDate->format('d')))
                ->setTime(0, 0, 0, 0);
            $comparisonYear = intval($checkOutDate->format('Y')) > intval($checkInDate->format('Y')) ? 0001 : 0000;
            $endDate        = (new DateTime())
                ->setDate($comparisonYear, intval($checkOutDate->format('m')), intval($checkOutDate->format('d')))
                ->setTime(0, 0, 0, 0);

            $summSt  = $settings->getSummerStart();
            $summEnd = $settings->getSummerEnd();
            if ($startDate > $summSt && $startDate < $summEnd && $endDate > $summSt && $endDate < $summEnd) {
                //Summer price - total stay in the range
                $seasonPrice = $daysOfVisit * $room->getBasePrice() * $summerDiscount;
            }
            if ($startDate < $summSt && $endDate < $summSt || $startDate > $summEnd && $endDate > $summEnd) {
                //Winter price - total stay outside the range
                $seasonPrice = $daysOfVisit * $room->getBasePrice();
            }
            if ($startDate < $summSt && $endDate > $summSt) {
                //Partial price - stay at the turn of winter and summer
                $winterDays = $summSt->diff($startDate)->days;
                $seasonPrice = $winterDays * $room->getBasePrice();
                $seasonPrice += ($daysOfVisit - $winterDays) * $room->getBasePrice() * $summerDiscount;
            }
        }

        $finalPrice     = round($seasonPrice * ((100 - $promoOfferDiscount - $finalStepsDiscount) / 100), 2);
        $tempPriceField = number_format($finalPrice, 2, ',', ' ').'zł ('
            .number_format(($finalPrice / $daysOfVisit) / $guests, 2, ',', ' ')
            .'zł os/noc)';

        return [
            'finalPrice'     => $finalPrice,
            'tempPriceField' => $tempPriceField,
        ] + $stepsParams;
    }
}
