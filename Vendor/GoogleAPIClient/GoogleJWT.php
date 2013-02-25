<?php

namespace GoogleAPIClient;

/**
 * Description of GoogleJWT
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleJWT {

    protected $header;
    protected $claims;
    protected $signature = false;
    protected $algorithm = "SHA256";

    public function __construct(array $header = array(), array $claims = array()) {
        $this->setHeader($header);
        $this->setClaims($claims);
    }

    public function setHeader(array $header) {
        $this->header = $header;
        return $this;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getClaims() {
        return $this->claims;
    }

    public function setClaims(array $claims) {
        $this->claims = $claims;
        return $this;
    }

    public function setAlgorithm($algorithm) {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function encode($data) {
        $b64 = json_encode($data);
        return str_replace(array('+', '/', '\r', '\n', '='), array('-', '_'), $b64);
    }

    public function getHeaderEncoded() {
        return $this->encode($this->getHeader());
    }

    public function getClaimsEncoded() {
        return $this->encode($this->getHeader());
    }

    public function generateSignature(&$pkeyid) {
        if (!$this->signature) {
            $data = "{$this->getHeaderEncoded()}.{$this->getClaimsEncoded()}";
            openssl_sign($data, $signature, $pkeyid, $this->algorithm);
            $this->signature = $this->encode($signature);
        }
        return $this;
    }

    public function getSignature() {
        return $this->signature;
    }

    public function __toString() {
        return "{$this->getHeaderEncoded()}.{$this->getClaimsEncoded()}.{$this->getSignature()}";
    }

    public function getString() {
        return $this->__toString();
    }

}