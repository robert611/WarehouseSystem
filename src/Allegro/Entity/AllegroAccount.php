<?php

namespace App\Allegro\Entity;

use App\Allegro\Repository\AllegroAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllegroAccountRepository::class)]
class AllegroAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $clientId;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $clientSecret;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $redirectUri;

    #[ORM\Column(type: 'smallint')]
    private bool $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(?string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public static function from(
        string $name,
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        bool $active
    ): self {
        $allegroAccount = new AllegroAccount();
        $allegroAccount->name = $name;
        $allegroAccount->clientId = $clientId;
        $allegroAccount->clientSecret = $clientSecret;
        $allegroAccount->redirectUri = $redirectUri;
        $allegroAccount->active = $active;

        return $allegroAccount;
    }
}
