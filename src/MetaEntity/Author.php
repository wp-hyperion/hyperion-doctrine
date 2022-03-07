<?php

namespace Hyperion\Doctrine\MetaEntity;

class Author
{
    private string $name;
    private string $email;
    private string $url;
    private string $ip;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Author
     */
    public function setEmail(string $email): Author
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Author
     */
    public function setUrl(string $url): Author
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return Author
     */
    public function setIp(string $ip): Author
    {
        $this->ip = $ip;
        return $this;
    }
}