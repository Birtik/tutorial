<?php declare(strict_types=1);


namespace App\Model;

use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserModel
{
    /**
     * @Assert\Email(
     *      message = "Email '{{ value }}' nie jest poprawny!"
     * )
     * @AppAssert\ContainsRepeatEmail()
     */
    private string $email;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern = "/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[@#$%^&+=])(?=\S+$).{6,}$/",
     *     message = "Niepoprawne hasło"
     * )
     */
    private string $password;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var bool
     */
    private bool $legalAgreement;

    /**
     * @var bool
     */
    private bool $newsletterAgreement;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return bool
     */
    public function isLegalAgreement(): bool
    {
        return $this->legalAgreement;
    }

    /**
     * @param bool $legalAgreement
     */
    public function setLegalAgreement(bool $legalAgreement): void
    {
        $this->legalAgreement = $legalAgreement;
    }

    /**
     * @return bool
     */
    public function isNewsletterAgreement(): bool
    {
        return $this->newsletterAgreement;
    }

    /**
     * @param bool $newsletterAgreement
     */
    public function setNewsletterAgreement(bool $newsletterAgreement): void
    {
        $this->newsletterAgreement = $newsletterAgreement;
    }
}