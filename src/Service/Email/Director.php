<?php declare(strict_types=1);


namespace App\Service\Email;

class Director
{
    /**
     * @var string
     */
    private string $from;

    public function __construct(string $from)
    {
        $this->from = $from;
    }

    /**
     * @param Builder $builder
     * @param $mailTo
     * @param string $token
     * @return mixed
     */
    public function build(Builder $builder, $mailTo, string $token = '')
    {
        $builder->createEmail();
        $builder->addFrom($this->from);
        $builder->addHtmlTemplate();
        $builder->addSubject();
        $builder->addContext($token);
        $builder->addTo($mailTo);

        return $builder->getEmail();
    }
}