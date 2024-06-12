<?php

/**
 * BaseHandler is the boilerplate of all curl requests to the SpotifyAPI.
 */
class BaseHandler
{
private bool $_returnTransfer;
private string $_encoding;
private int $_maxRedirects;
private int $_timeout;
private bool $_followLocation;
private string $_version;
private string $_request;
private array $_header;

    /**
     * @param bool $_returnTransfer set return transfer (true ->return false->output directly)
     * @param string $_encoding type of message encoding
     * @param int $_maxRedirects maximum amount of api redirects
     * @param int $_timeout duration before timeout
     * @param bool $_followLocation can the request follow relative/absolute urls
     * @param string $_version which version of curl to use
     * @param string $_request request type (get, post...)
     * @param array $_header content of header
     */
    public function __construct(bool $_returnTransfer, string $_encoding, int $_maxRedirects, int $_timeout,
                                bool $_followLocation, string $_version, string $_request, array $_header)
    {
        $this->_returnTransfer = $_returnTransfer;
        $this->_encoding = $_encoding;
        $this->_maxRedirects = $_maxRedirects;
        $this->_timeout = $_timeout;
        $this->_followLocation = $_followLocation;
        $this->_version = $_version;
        $this->_request = $_request;
        $this->_header = $_header;
    }

    /**
     * @return bool
     */
    public function isReturnTransfer(): bool
    {
        return $this->_returnTransfer;
    }

    /**
     * @param bool $returnTransfer
     */
    public function setReturnTransfer(bool $returnTransfer): void
    {
        $this->_returnTransfer = $returnTransfer;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->_encoding;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding(string $encoding): void
    {
        $this->_encoding = $encoding;
    }

    /**
     * @return int
     */
    public function getMaxRedirects(): int
    {
        return $this->_maxRedirects;
    }

    /**
     * @param int $maxRedirects
     */
    public function setMaxRedirects(int $maxRedirects): void
    {
        $this->_maxRedirects = $maxRedirects;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->_timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->_timeout = $timeout;
    }

    /**
     * @return bool
     */
    public function isFollowLocation(): bool
    {
        return $this->_followLocation;
    }

    /**
     * @param bool $followLocation
     */
    public function setFollowLocation(bool $followLocation): void
    {
        $this->_followLocation = $followLocation;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->_version = $version;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->_request;
    }

    /**
     * @param string $request
     */
    public function setRequest(string $request): void
    {
        $this->_request = $request;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->_header;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header): void
    {
        $this->_header = $header;
    }



}