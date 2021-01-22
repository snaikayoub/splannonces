<?php

namespace App\Entity;


use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity(
 *      "username",
 *      message="Le propriétaire de cet email est déja inscrit, consultez la rubrique mot de passe oublié"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Email
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Assert\EqualTo(propertyPath="username",message="Vous n'avez pas saisie le meme Email")
     */
    public $confirm_username;

    /**
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Votre mot de passe doit faire au minimum 6 caractères"
     *      )
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password",message="Vous n'avez pas saisie le meme Mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @Assert\Regex(
     *      pattern = "/^0[0-9]{9}/i",
     *      message = "Veuillez saisire un Numéro de Téléphone valide"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $organisation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileprofil;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="annonces_img", fileNameProperty="fileprofil")
     * @Assert\Image(
     *  mimeTypes = "image/jpeg",
     *  mimeTypesMessage = "Seul les images de Type JPG/JPEG sont acceptées"
     * )
     * @var File|null
     */
    private $imgprofil;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getOrganisation(): ?string
    {
        return $this->organisation;
    }

    public function setOrganisation(string $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
    /**
     * String representation of Object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the String representation of Object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->ville,
            $this->telephone,
            $this->nom,
            $this->prenom,
            $this->organisation,
            $this->fileprofil

        ]);
    }

    /**
     * Constructs the object
     * @param string $serialized <p>
     * The string representation of Object
     * </p>
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->ville,
            $this->telephone,
            $this->nom,
            $this->prenom,
            $this->organisation,
            $this->fileprofil

        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imgprofil
     */
    public function setImgprofil(?File $imgprofil = null): void
    {

        $this->imgprofil = $imgprofil;

        if (null !== $imgprofil) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImgprofil(): ?File
    {
        return $this->imgprofil;
    }

    public function getFileprofil(): ?string
    {
        return $this->fileprofil;
    }

    public function setFileprofil(?string $fileprofil): self
    {
        $this->fileprofil = $fileprofil;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
