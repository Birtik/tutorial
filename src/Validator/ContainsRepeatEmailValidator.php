<?php declare(strict_types=1);

namespace App\Validator;

use App\Repository\UserRepository;
use App\Service\Email\EmailManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsRepeatEmailValidator extends ConstraintValidator
{
    /**
     * @var EmailManager
     */
    private EmailManager $emailSender;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, EmailManager $emailSender)
    {
        $this->userRepository = $userRepository;
        $this->emailSender = $emailSender;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsRepeatEmail) {
            throw new UnexpectedTypeException($constraint, ContainsRepeatEmail::class);
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if (null !== $user) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();

            $this->emailSender->sendDoubleRegistrationAlertEmail($value);
        }
    }
}
