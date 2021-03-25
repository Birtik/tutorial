<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Entity\Token;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Factory\UserFactory;
use App\Form\RegisterType;
use App\Model\RegisterUserModel;
use App\Repository\TokenRepository;
use App\Service\AgreementsManager;
use App\Service\UserRegisterManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $em;

    private UserRegisterManager $userRegisterManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em,
        UserRegisterManager $userRegisterManager
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->userRegisterManager = $userRegisterManager;
    }

    /**
     * @Route("/registration", name="app_registration")
     * @param Request $request
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     * @throws Exception
     */
    public function registration(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $registeredUserModel = new RegisterUserModel();
        $form = $this->createForm(RegisterType::class, $registeredUserModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRegisterManager->setUserData($registeredUserModel);
            $agreement = $this->userRegisterManager->setUserAgreement($registeredUserModel, $user);

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            try {
                $this->em->beginTransaction();
                $this->em->persist($user);
                $this->em->persist($agreement);
                $this->em->flush();

                $event = new UserRegisteredEvent($user);
                $dispatcher->dispatch($event, UserRegisteredEvent::NAME);

                $this->em->flush();
                $this->em->commit();
            } catch (Exception $exception) {
                $this->em->rollback();
                throw $exception;
            }

            return $this->redirectToRoute("app_complete_registration");
        }

        return $this->render(
            'registration/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/complete/registration", name="app_complete_registration")
     * @return Response
     */
    public function completeRegistration(): Response
    {
        return $this->render('registration/confirm.html.twig');
    }

    /**
     * @Route("/confirm/email/{value}", name="app_confirm_email")
     * @param TokenRepository $tokenRepository
     * @param string $value
     * @return RedirectResponse
     * @throws NonUniqueResultException
     */
    public function confirmEmail(
        TokenRepository $tokenRepository,
        string $value
    ): RedirectResponse {
        $token = $tokenRepository->findWithUser($value, Token::TYPE_REGISTER);

        if ($token === null || $token->getExpiredAt() < (new \DateTime())) {
            $this->addFlash(
                'notice',
                'Token wygasł'
            );

            return $this->redirectToRoute('app_login');
        }
        $token->setUsedAt(new \DateTime());
        $user = $token->getUser();
        $user->setEnabled(true);
        $this->em->flush();

        $this->addFlash(
            'success',
            'Konto zostało aktywowane. Witamy!'
        );

        return $this->redirectToRoute('app_login');
    }
}
