<?php

namespace App\Allegro\Entity;

use App\Allegro\Repository\AllegroAccountRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AllegroAccountRepository::class)]
class AllegroAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['allegro_account:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['allegro_account:read'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $clientId;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $clientSecret;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $redirectUri;

    #[ORM\Column(type: 'smallint')]
    private bool $active;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private ?string $refreshToken;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private ?string $accessToken;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $expiresAt;

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


    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
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

    public function updateRefreshToken(string $refreshToken, string $accessToken, int $expiresIn): void
    {
        $dateTime = new DateTimeImmutable();
        $dateTime = $dateTime->add(new DateInterval("PT{$expiresIn}S"));

        $this->refreshToken = $refreshToken;
        $this->accessToken = $accessToken;
        $this->expiresAt = $dateTime;
    }

    public function getBasicToken(): string
    {
        return base64_encode($this->getClientId() . ':' . $this->getClientSecret());
    }
}
