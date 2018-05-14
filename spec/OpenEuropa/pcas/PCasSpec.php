<?php
namespace spec\OpenEuropa\pcas;

use OpenEuropa\pcas\PCas;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

class PCasSpec extends ObjectBehavior
{
    public function getProperties()
    {
        return [
            'protocol' => [
                'login' => [
                    'uri' => 'http://cas-server/login',
                    'allowed_parameters' => [
                        'service',
                    ],
                ],
                'logout' => [
                    'uri' => 'http://cas-server/logout',
                    'allowed_parameters' => [
                        'service',
                    ],
                ],
            ]
        ];
    }

    public function setUpClient()
    {
        $_SERVER['HTTP_HOST'] = 'cas-client';
        $_SERVER['REQUEST_URI'] = '/';
    }

    public function let()
    {
        $this->setUpClient();
        $this->beConstructedWith($this->getProperties());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PCas::class);
    }

    public function it_can_login()
    {
        $properties = $this->getProperties();
        $url = sprintf('%s?service=%s', $properties['protocol']['login']['uri'], urlencode(sprintf('http://%s/', $_SERVER['HTTP_HOST'])));

        $this->loginUrl()->__toString()->shouldBe($url);
        $this->login()->shouldBeAnInstanceOf(ResponseInterface::class);
        $this->login()->getStatusCode()->shouldBe(302);
    }

    public function it_can_logout()
    {
        $properties = $this->getProperties();
        $url = sprintf('%s?service=%s', $properties['protocol']['logout']['uri'], urlencode(sprintf('http://%s/', $_SERVER['HTTP_HOST'])));

        $this->logoutUrl()->__toString()->shouldBe($url);
        $this->logout()->shouldBeNull();
    }
}