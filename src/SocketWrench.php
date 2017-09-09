<?php

namespace DanAlloway\SocketWrench;

use RuntimeException;

final class SocketWrench
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $eol;

    /**
     * @var int
     */
    protected $errno;

    /**
     * @var string
     */
    protected $errstr;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @param string $address
     * @param int    $port
     * @param string $eol
     * @param int    $timeout
     */
    public function __construct($address, $port = -1, $eol = null, $timeout = 30)
    {
        $this->address = $address;
        $this->port = $port;
        $this->eol = is_null($eol) ? chr(10) : $eol;
        $this->timeout = $timeout;
    }

    /**
     * Write to a socket connection.
     *
     * @param  string|array $message
     * @return string
     */
    public function write($message)
    {
        $socket = $this->connect();

        foreach ((array) $message as $line) {
            fwrite($socket, $line);
            fwrite($socket, $this->eol);
        }

        $response = $this->read($socket);

        $this->disconnect($socket);

        return $response;
    }

    /**
     * Establish a socket connection.
     *
     * @return resource
     */
    protected function connect()
    {
        $socket = fsockopen(
            $this->address,
            $this->port,
            $this->errno,
            $this->errstr,
            $this->timeout
        );

        if (!$socket) {
            throw new RuntimeException(
                sprintf('Unable to connect to server: (%s) %s', $this->errno, $this->errstr)
            );
        }

        stream_set_blocking($socket, 0);

        return $socket;
    }

    /**
     * Disconect an open socket connection.
     *
     * @param  resource $socket
     * @return bool
     */
    protected function disconnect($socket)
    {
        return is_resource($socket) ? fclose($socket) : false;
    }

    /**
     * Read from an open socket connection.
     *
     * @param  resource $socket
     * @return string
     */
    protected function read($socket)
    {
        $response = stream_get_contents($socket);

        if (!$response) {
            throw new RuntimeException(
                sprintf('Error reading from the socket: (%s) %s', $this->errno, $this->errstr)
            );
        }

        return $response;
    }
}
