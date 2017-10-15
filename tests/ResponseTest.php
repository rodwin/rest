<?php

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testInstantiationResponseOnly()
    {
        $rawResponse = 'HTTP/1.1 200 OK
Date: Sun, 15 Oct 2017 09:08:45 GMT
Content-Type: application/json; charset=utf-8
Content-Length: 292
Connection: keep-alive
Set-Cookie: __cfduid=da3394200e70ef444ffd875ddc251a6e41508058525; expires=Mon, 15-Oct-18 09:08:45 GMT; path=/; domain=.typicode.com; HttpOnly
X-Powered-By: Express
Vary: Origin, Accept-Encoding
Access-Control-Allow-Credentials: true
Cache-Control: public, max-age=14400
Pragma: no-cache
Expires: Sun, 15 Oct 2017 13:08:45 GMT
X-Content-Type-Options: nosniff
Etag: W/"124-yiKdLzqO5gfBrJFrcdJ8Yq0LGnU"
Via: 1.1 vegur
CF-Cache-Status: HIT
Server: cloudflare-nginx
CF-RAY: 3ae1a2b842f90dc3-MAD

{
  "userId": 1,
  "id": 1,
  "title": "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
  "body": "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
}';

        $response = new \OtherCode\Rest\Payloads\Response($rawResponse);
        $this->assertEquals('application/json', $response->content_type);
        $this->assertEquals('utf-8', $response->charset);
        $this->assertEquals('{
  "userId": 1,
  "id": 1,
  "title": "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
  "body": "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
}', $response->body);

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertCount(17, $response->headers);
    }

    public function testInstantiationWithError()
    {
        $response = new \OtherCode\Rest\Payloads\Response(null, new \OtherCode\Rest\Core\Error(500, "Server Error"));
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);
        $this->assertEquals(500, $response->error->code);
        $this->assertEquals('Server Error', $response->error->message);
    }

    public function testInstantiationWithMetadata()
    {
        $response = new \OtherCode\Rest\Payloads\Response(null, null, array(
            'http_code' => 200
        ));
        $this->assertInternalType('array', $response->metadata);
        $this->assertCount(1, $response->metadata);
        $this->assertEquals(200, $response->metadata['http_code']);
        $this->assertEquals(200, $response->code);
    }
}