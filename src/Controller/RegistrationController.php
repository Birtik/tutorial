<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\RegisterType;
use App\Repository\TokenRepository;
use App\Security\LoginFormAuthenticator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
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
        $newUser = new User();
        $form = $this->createForm(RegisterType::class, $newUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newUser->setPassword(
                $this->passwordEncoder->encodePassword(
                    $newUser,
                    $newUser->getPassword()
                )
            );

            try {
                $this->em->beginTransaction();
                $this->em->persist($newUser);
                $this->em->flush();

                $event = new UserRegisteredEvent($newUser);
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
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param string $value
     * @return RedirectResponse
     */
    public function confirmEmail(TokenRepository $tokenRepository,LoginFormAuthenticator $loginFormAuthenticator, string $value): RedirectResponse
    {
        $token = $tokenRepository->findWithUser($value, Token::TYPE_REGISTER);

        if ($token === null || $token->getExpiredAt() < (new DateTimeImmutable())) {
            $this->addFlash(
                'notice',
                'Token wygasł'
            );

            return $this->redirectToRoute('app_login');
        }

        $token->setUsedAt(new DateTimeImmutable());
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
