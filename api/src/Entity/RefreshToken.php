<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('refresh_tokens')]
class RefreshToken
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    protected ?string $refreshToken = null;

    #[ORM\Column]
    protected ?string $username = null;

    #[ORM\Column]
    protected ?\DateTimeInterface $valid = null;

    /**
     * Creates a new model instance based on the provided details.
     */
    public static function createForUserWithTtl(string $refreshToken, User $user, int $ttl): RefreshToken
    {
        $valid = new \DateTime();
            $valid->modify('+'.$ttl.' seconds');

        $model = new static();
        $model->setRefreshToken($refreshToken);
        $model->setUsername(method_exists($user, 'getUserIdentifier') ? $user->getUserIdentifier() : $user->getUsername());
        $model->setValid($valid);

        return $model;
    }

    /**
     * @return string Refresh Token
     */
    public function __toString()
    {
        return $this->getRefreshToken() ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefreshToken($refreshToken = null)
    {
        if (null === $refreshToken || '' === $refreshToken) {
            trigger_deprecation('gesdinet/jwt-refresh-token-bundle', '1.0', 'Passing an empty token to %s() to automatically generate a token is deprecated.', __METHOD__);

            $refreshToken = bin2hex(random_bytes(64));
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return null !== $this->valid && $this->valid >= new \DateTime();
    }
}