<?php

namespace Stampie\Carrier;

use Stampie\Request;
use Stampie\Response;
use Stampie\Identity;
use Stampie\Message;
use Stampie\Utils;

class PostmarkCarrier extends AbstractCarrier
{
    public function createRequest(Identity $to, Message $message)
    {
        return Request::create('http://api.postmarkapp.com/email')
            ->setContent($this->format($to, $message))
            ->setHeaders([
                'Accept'                  => 'application/json',
                'Content-Type'            => 'application/json',
                'X-Postmark-Server-Token' => $this->key,
            ])
        ;
    }

    public function handleResponse(Response $response)
    {
        if ($response->isSuccessful()) {
            return json_decode($response->getContent())->MessageID;
        }

        throw Utils::convertResponseToException($response);
    }

    private function format(Identity $to, Message $message)
    {
        $parameters = [
            'To'       => (string) $to,
            'From'     => (string) $message->getFrom(),
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'Headers'  => $message->getHeaders(),
        ];

        if ($attachments = $message->getAttachments()) {
            $parameters['Attachments'] = array_map([$this, 'formatAttachment'], $attachments);
        }

        return json_encode($parameters);
    }

    private function formatAttachment(Attachment $attachment)
    {
        return [
            'Name'        => $attachment->getName(),
            'Content'     => $attachment->getEncodedContent(),
            'ContentType' => $attachment->getContentType(),
        ];
    }
}
